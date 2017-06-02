pipeline {
    agent { docker 'php' }
    stages {
        stage('build') {
            steps {
                sh 'whoami'
                sh 'phpunit'
            }
        }
    }
}