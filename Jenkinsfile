pipeline {
    agent { docker 'php' }
    stages {
        stage('build') {
            steps {
                sh 'phpunit'
            }
        }
    }
}  