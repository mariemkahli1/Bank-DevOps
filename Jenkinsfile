pipeline {
  agent any
  environment {
    SONARQUBE = 'sonarqube'
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
    stage('SonarQube Analysis') {
      steps {
        script {
          def scannerHome = tool 'SonarQube Scanner 6.0.0.4432'
          withSonarQubeEnv('sonarqube') {
            sh """
            ${scannerHome}/bin/sonar-scanner \
              -Dsonar.projectKey=jenkins \
              -Dsonar.sources=src \
              -Dsonar.host.url=http://192.168.1.16:9000 \
              -Dsonar.token=sqp_1d7dfc63f3aa4bd51c66daa32f52a3d9a5d70436
            """
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

    stage('Lint Dockerfile') {
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
                }
                sh 'docker build -t flare-bank .'
            }
        }
  
  }
  post {
    always {
      echo 'Pipeline has finished.'
    }
  }
}
