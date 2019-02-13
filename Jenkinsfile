pipeline {

    agent any

    environment {
        BUILD_ENV = 'Dev'
        SELINUX = 'disabled'
        AWS_ID  = 'ecr:eu-west-1:aws-ecr'
        ECR_REPO_NGINX = '<!!COMPLETE-ME!!>.dkr.ecr.eu-west-1.amazonaws.com/service-nginx'
        ECR_REPO_PHP = '<!!COMPLETE-ME!!>.dkr.ecr.eu-west-1.amazonaws.com/service-php'
    }

    stages {

        stage('Init') {
            steps {
                script {
                    env.TAG_NAME = env.BRANCH_NAME.replaceAll("/","-");
                }
                echo "\u2756 Init"
                echo " Build Url     :: ${env.BUILD_URL}"
                echo " Jenkins Url   :: ${env.JENKINS_URL}"
                echo " Build Tag     :: ${env.BUILD_TAG}"
                echo " Job Name      :: ${env.JOB_NAME}"
                echo " Job Base Name :: ${env.JOB_BASE_NAME} "
                echo " Branch Name   :: ${env.BRANCH_NAME}"
                echo " Git Hash      :: ${env.GIT_COMMIT}"
                checkout scm
                echo " Tag Name      :: ${env.TAG_NAME}"

            }
        }

        stage("Docker Build ") {
            steps {
                    sh '''
                        cp .env.example .env
                        docker-compose -f docker-compose-test.yml build
                        docker-compose -f docker-compose-test.yml up -d
                        sleep 30
                        '''
            }
        }

        stage('Unit Test') {
            steps {
                script {
                echo "\u2756 Running unit test"
                    try {
                        bitbucketStatusNotify(buildState: 'INPROGRESS')

                        echo "Running unit test for :: ${env.GIT_BRANCH} "
                        sh(" docker-compose -f docker-compose-test.yml run php sh -c '/scripts/unit_test.sh' " )

                        bitbucketStatusNotify(buildState: 'SUCCESSFUL')
                        echo "\u2705 Unit test OK :: ${env.JOB_NAME}"

                    } catch (Exception e) {

                        bitbucketStatusNotify(buildState: 'FAILED')
                        sh(" docker-compose -f docker-compose-test.yml down ")
                        echo "\u274C Unit test failed :: ${env.JOB_NAME}"
                        sh("exit 1")
                    }
                }
            }
        }

        stage('API Test') {
            steps {
                script {
                echo "\u2756 Running API Test"
                    try {
                        bitbucketStatusNotify(buildState: 'INPROGRESS')

                        echo "Running API test for :: ${env.GIT_BRANCH} "
                        sh(" docker-compose -f docker-compose-test.yml run php sh -c '/scripts/api_test.sh' ")

                        echo "Stop container after test "
                        sh(" docker-compose -f docker-compose-test.yml down ")

                        bitbucketStatusNotify(buildState: 'SUCCESSFUL')
                        echo "\u2705 API test OK :: ${env.JOB_NAME}"

                    } catch (Exception e) {

                        bitbucketStatusNotify(buildState: 'FAILED')
                        sh(" docker-compose -f docker-compose-test.yml down ")
                        echo "\u274C API test failed  :: ${env.JOB_NAME}"
                        sh("exit 1")

                    }
                }
            }
        }

        stage('Build & Push Docker images') {
            when {
                branch 'development'
            }
            steps {
                    sh '''
                        $(aws ecr get-login --no-include-email --region eu-west-1)
                        docker build -t "${ECR_REPO_PHP}":"${GIT_COMMIT}" -f docker/php/test/Dockerfile .
                        docker push "${ECR_REPO_PHP}":"${GIT_COMMIT}"

                        docker build -t "${ECR_REPO_NGINX}":"${GIT_COMMIT}" -f docker/nginx/test/Dockerfile .
                        docker push "${ECR_REPO_NGINX}":"${GIT_COMMIT}"
                    '''
            }
        }
    }
}
