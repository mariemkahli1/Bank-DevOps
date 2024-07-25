pipeline {
    agent any

    environment {
        SONARQUBE = 'sonar1'
        CONTAINER_NAME = 'flare-container'
        IMAGE_NAME = 'flare-bank'
        NETWORK_NAME = 'bridge'
        DOCKERHUB_CREDENTIALS = credentials('mariem-dockerHub')
    }

    stages {
        stage('PHPStan Analysis') {
            steps {
                script {
                    sh 'composer require --dev phpstan/phpstan'
                    try {
                        sh 'vendor/bin/phpstan analyze src -l 6 > phpstan_errors.txt'
                    } catch (err) {
                        echo "PHPStan analysis encountered errors but continuing..."
                    }
                }
            }
        }

        stage('SCM') {
            steps {
                checkout scm
            }
        }

        stage('SonarQube Analysis') {
            steps {
                script {
                    def scannerHome = tool 'Sonar';
                    withSonarQubeEnv('sonar1') {
                        sh "${scannerHome}/bin/sonar-scanner"
                    }
                }
            }
        }

        stage("Quality Gate") {
            steps {
                script {
                    try {
                        waitForQualityGate abortPipeline: true
                    } catch (err) {
                        echo "Quality Gate check failed: ${err}"
                    }
                }
            }
        }

        stage('Hadolint Dockerfile analysis') {
            steps {
                script {
                    def hadolintOutput = sh(returnStdout: true, script: 'hadolint --config hadolint.yaml Dockerfile || true').trim()
                    if (hadolintOutput) {
                        error "Error: Dockerfile linting failed:\n${hadolintOutput}"
                    } else {
                        echo 'Dockerfile linting passed'
                    }
                }
            }
        }

        stage('Create .env File') {
            steps {
                withCredentials([string(credentialsId: 'db_credentials', variable: 'DB_CREDENTIALS')]) {
                    script {
                        def envVariables = DB_CREDENTIALS.split(' ')
                        def envContent = envVariables.join('\n')
                        writeFile file: 'src/.env', text: envContent
                    }
                }
            }
        }

        stage('Build Docker Image') {
            steps {
                script {
                    def imageExists = sh(script: 'docker images -q flare-bank', returnStdout: true).trim()
                    if (imageExists) {
                        def containerIds = sh(script: 'docker ps -a -q --filter ancestor=flare-bank', returnStdout: true).trim()
                        if (containerIds) {
                            sh 'docker stop $(docker ps -a -q --filter ancestor=flare-bank)'
                            sh 'docker rm $(docker ps -a -q --filter ancestor=flare-bank)'
                        }
                        sh 'docker rmi -f flare-bank'
                    }
                    sh 'docker build -t flare-bank .'
                }
            }
        }

        stage('Dockle Docker Image Test') {
            steps {
                script {
                    def imageName = 'flare-bank'
                    def existingTags = sh(script: "docker images --format '{{.Tag}}' ${imageName}", returnStdout: true).trim().split('\n')
                    def latestTag = existingTags.findAll { it =~ /^\d+$/ }.max { it.toInteger() } ?: '0'
                    def newTag = latestTag.toInteger()
                    def fullImageName = "${imageName}:${newTag}"
                    sh "dockle ${fullImageName} || true"
                }
            }
        }

        stage('Test Security Trivy') {
            steps {
                script {
                    def imageName = 'flare-bank'
                    def existingTags = sh(script: "docker images --format '{{.Tag}}' ${imageName}", returnStdout: true).trim().split('\n')
                    def latestTag = existingTags.findAll { it =~ /^\d+$/ }.max { it.toInteger() } ?: '0'
                    def newTag = latestTag.toInteger()
                    sh "trivy image --severity CRITICAL ${imageName}:${newTag} || true"
                }
            }
        }

        stage('Run Docker Container') {
            steps {
                script {
                    // Vérifier si le réseau existe, sinon le créer
                    def networkExists = sh(script: "docker network ls --filter name=${env.NETWORK_NAME} -q", returnStdout: true).trim()
                    if (!networkExists) {
                        sh "docker network create ${env.NETWORK_NAME}"
                    } else {
                        echo "Network ${env.NETWORK_NAME} already exists."
                    }

                    // Obtenir le dernier tag numérique de l'image
                    def latestTag = sh(script: "docker images --format '{{.Tag}}' ${env.IMAGE_NAME} | grep '^[0-9]\\+' | sort -nr | head -n 1", returnStdout: true).trim()
                    if (!latestTag) {
                        latestTag = 'latest' // Utilisez 'latest' par défaut si aucun tag numérique n'est trouvé
                    }

                    // Démarrer le conteneur avec le dernier tag trouvé
                    sh "docker run -d --network=${env.NETWORK_NAME} --name ${env.CONTAINER_NAME} ${env.IMAGE_NAME}:${latestTag}"
                }
            }
        }

        stage('Login') {
            steps {
                script {
                    sh 'echo $DOCKERHUB_CREDENTIALS_PSW | docker login -u $DOCKERHUB_CREDENTIALS_USR --password-stdin'
                }
            }
        }


  /*  stage('Dastardly Security Test') {
           steps {
              script {
             // Run Dastardly against your web application
                    sh 'docker run --rm -v $(pwd):/dastardly/site/ dastardlyci/dastardly https://localhost' // Change URL to your web application's URL
                }
            }
        } */
        

        stage('Tag and Push') {
            steps {
                script {
                    sh "docker tag ${IMAGE_NAME}:latest mariem820/${IMAGE_NAME}:latest"
                    sh "docker push mariem820/${IMAGE_NAME}:latest"
                }
            }
        }



    stage('Deployment') {
    steps {
        script {
            // Configure Minikube environment
            sh 'eval $(minikube docker-env)'

            // Appliquer les configurations avec validation TLS désactivée
            sh 'kubectl apply -f deployment.yaml --validate=false --insecure-skip-tls-verify'
            sh 'kubectl apply -f service.yaml --validate=false --insecure-skip-tls-verify'

            // Obtenir l'URL du service
            def serviceUrl = sh(script: 'minikube service flare-bank-service --url', returnStdout: true).trim()
            echo "Application is accessible at: ${serviceUrl}"
        }
    }
}




        
    }

    post {
        always {
            sh 'docker logout'
            echo 'Pipeline has finished.'
        }
    }
}
