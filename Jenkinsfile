pipeline {
    agent {
        label 'docker-host'
    }
    stages {
        stage("Install & Build") {
            steps {
                sh 'composer install --prefer-dist --no-progress --no-suggest'
                sh 'chmod -R 777 var'
                sh 'mkdir -p var/data'
                sh 'touch /var/data/data.sqlite'
            }
        }

        stage("Unit Tests") {
            steps {
                sh 'composer run-script tests'
            }
        }

    }

    post {

        aborted {
            // Do nothing but just echo that it was aborted.
            echo "CI run was manually aborted."
        }

        cleanup {
            // Delete workspace directories to handle disk usage
            dir("$WORKSPACE") {
                // Change owner of all files in workspace so we can delete
                // docker created files which are owned by root.
                sh "sudo chown -R $USER:$USER ."
                deleteDir()
            }

            dir("$WORKSPACE@tmp") {
                deleteDir()
            }
        }
    }
}