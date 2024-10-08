pipeline {
    agent any

    environment {
        SONARQUBE = 'sonar1'
        CONTAINER_NAME = 'flare-container'
        IMAGE_NAME = 'flare-bank'
        NETWORK_NAME = 'bridge'
        DOCKERHUB_CREDENTIALS = credentials('mariem-dockerHub')
       // HELM_VERSION = '3.8.0' // Spécifiez la version de Helm si nécessaire
         // KUBECONFIG = credentials('kubeconfig')
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
                    sh 'cp metrics.php src/'
                }
            }
        }



        

  stage('SCM') {
      steps{
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
                    echo 'Linting Dockerfile...'
                    try {
                        def hadolintOutput = sh(returnStdout: true, script: 'hadolint --config hadolint.yaml Dockerfile || true').trim()
                        if (hadolintOutput) {
                            error "Dockerfile linting failed:\n${hadolintOutput}"
                        } else {
                            echo 'Dockerfile linting passed.'
                        }
                    } catch (err) {
                        echo "Error during Dockerfile linting: ${err}"
                        currentBuild.result = 'FAILURE'
                        error "Dockerfile linting failed."
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

        stage('Dockle Docker Image analysis') {
            steps {
                script {
                    sh "dockle flare-bank:latest || true"
                }
            }
        }

          stage('Test Security Trivy') {
            steps {
                script {
                    sh "trivy image --severity CRITICAL flare-bank:testing || true"
                }
            }
        }

      /*  stage('Run Docker Container') {
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
        } */

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
            echo 'Start deploying'
            try {
                // Supprimer et recréer Minikube pour éviter les problèmes de configuration
                echo "Deleting and recreating Minikube..."
                sh 'minikube delete || true'
                sh 'minikube start --driver=docker'

                // Configurer l'environnement Docker pour Minikube
                sh 'eval $(minikube docker-env)'
                
                // Tirer la dernière image Docker
                sh 'docker pull mariem820/flare-bank:latest'

                // Appliquer les fichiers YAML de déploiement et de service
                sh 'kubectl apply -f deployment.yaml --validate=false'
                sh 'kubectl apply -f service.yaml --validate=false'

                // Attendre que les pods soient prêts
                timeout(time: 5, unit: 'MINUTES') {
                    waitUntil {
                        def podsReady = sh(script: 'kubectl get pods -l app=flare-bank -o jsonpath="{.items[*].status.containerStatuses[*].ready}"', returnStdout: true).trim()
                        return podsReady.contains('true')
                    }
                }

                // Afficher l'état des pods
                sh 'kubectl get pods -o wide'

                // Afficher les logs des pods
                sh 'kubectl logs -l app=flare-bank'

                // Obtenir l'URL du service
                def url = sh(script: 'minikube service flare-bank-service --url', returnStdout: true).trim()
                echo "Application is accessible at: ${url} || true"
            } catch (err) {
                echo "Error deploying to Minikube: ${err}"
                currentBuild.result = 'FAILURE'
                error "Deployment to Minikube failed."
            }
        }
    }
}


         stage('Monitoring Setup') {
            steps {
                script {
                    echo 'Setting up Prometheus and Grafana'
                    try {
                        sh 'kubectl apply -f prometheus-deployment.yaml --validate=false'
                        sh 'kubectl apply -f prometheus-configmap.yaml --validate=false'
                        sh 'kubectl apply -f prometheus-service.yaml --validate=false'
                        sh 'kubectl apply -f grafana-deployment.yaml --validate=false'
                        sh 'kubectl apply -f grafana-service.yaml --validate=false'

                        timeout(time: 10, unit: 'MINUTES') {
                            waitUntil {
                                def prometheusReady = sh(script: 'kubectl get pods -l app=prometheus -o jsonpath="{.items[*].status.phase}"', returnStdout: true).trim()
                                def grafanaReady = sh(script: 'kubectl get pods -l app=grafana -o jsonpath="{.items[*].status.phase}"', returnStdout: true).trim()
                                return prometheusReady.contains('Running') && grafanaReady.contains('Running')
                            }
                        }

                        def prometheusNodePort = sh(script: 'kubectl get svc prometheus-service -o jsonpath="{.spec.ports[0].nodePort}"', returnStdout: true).trim()
                        def grafanaNodePort = sh(script: 'kubectl get svc grafana-service -o jsonpath="{.spec.ports[0].nodePort}"', returnStdout: true).trim()
                        def minikubeIp = sh(script: 'minikube ip', returnStdout: true).trim()

                        if (!prometheusNodePort || !grafanaNodePort) {
                            error "Failed to retrieve NodePorts for Prometheus and/or Grafana services."
                        }

                        def prometheusUrl = "http://${minikubeIp}:${prometheusNodePort}"
                        def grafanaUrl = "http://${minikubeIp}:${grafanaNodePort}"

                        echo "Prometheus is accessible at: ${prometheusUrl}"
                        echo "Grafana is accessible at: ${grafanaUrl}"

                    } catch (err) {
                        echo "Error setting up Prometheus and Grafana: ${err}"
                        currentBuild.result = 'FAILURE'
                        error "Setup of Prometheus and Grafana failed."
                    }
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
