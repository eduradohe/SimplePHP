node('maven') {
	def app = 'app'
	def cliente = 'cliente'
	def gitUrl = 'http://gogs-ci-cd.apps.example.com/gogsadmin/' + cliente + '.git'

    stage 'Build image and deploy to dev'
    echo 'Building docker image'
    buildApp(app + '-dev', gitUrl, app)

    stage 'Deploy to QA'
    echo 'Deploying to QA'
    deploy(app + '-dev', app + '-qa', app)

    stage 'Wait for approval'
    input 'Aprove to production?'

    stage 'Deploy to Prod'
    deploy(app + '-dev', app, app)
}

def buildApp(String project, String gitUrl, String app){
    projectSet(project)

    sh "oc new-build php:5.6~${gitUrl} --name=${app}"
    sh "oc logs -f bc/${app}"
    sh "oc new-app ${app}"
    sh "oc expose service ${app} || echo 'Service already exposed'"
    sh "sleep 10"
}

def projectSet(String project) {
	sh "oc login --insecure-skip-tls-verify=true -u admin -p redhat@123 https://ocp-master.example.com:8443"
    sh "oc new-project ${project} || echo 'Project exists'"
    sh "oc project ${project}"
}

def deploy(String origProject, String project, String app){
    projectSet(project)
    sh "oc policy add-role-to-user system:image-puller system:serviceaccount:${project}:default -n ${origProject}"
    sh "oc tag ${origProject}/${app}:latest ${project}/${app}:latest"
    appDeploy(app)
}

def appDeploy(String app){
    sh "oc new-app ${app} -l app=${app} || echo 'Aplication already Exists'"
    sh "oc expose service ${app} || echo 'Service already exposed'"
}
