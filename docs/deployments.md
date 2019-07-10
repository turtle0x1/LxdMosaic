## Deployments

Deployments are a way of grouping different "types" of containers.

Deployments are two things:

 - A collection of cloud-config files
 - Containers that have been deployed

## First Deployment

### Create deployment

**First you must have atleast 1 cloud config that can be used in the deployment please go [here](./cloud-config.md)**

Head over to the deployments section of your LXDMosaic instance to create your deployment.

Click Create & give the deployment a name like "My_Site"

Then you define the "cloud-configs" that are part of this deployment,
you may have for example created two cloud-configs:

 - Webservers/My_Site (An apache2 server with your website)
 - Mysql/My_Site (A mysql server to host your websites data)

Which you would add into the deployment.

Once you have clicked create, the pop should disapear and the overview reload,
show your "My_Site" deployment with the active memory & containers

### Deploy Instances

If you then select your "My_Site" deployment site from the sidebar you will be
shown:

 - A row of buttons
 - cloud-configs & container instances in this deployment.  

We can now "Deploy Containers" to hosts of our liking.

Each cloud-config you have added to the deployment will be shown as an option,
you can define the number of each instance you want and the hosts to run on.

Typically you will need to:

 - Include your default profile as an "extra profile"
 - Check the containers are ready to use

### Start Deployment

Once you have deployed your instances you should see your new containers appear,
you can now click "Start Deployment", which will start all the instances in this
deployment.

Currently it is **your job** to check the cloud-config instructions were applied
succesfully to each container, it is on the todo list to work out how to succesfully
capture the result of cloud-configs `phone_home` as it doesn't work with self-signed
certificates.

It is also your job to route traffic (if required) to these instances, we have
intention to bake in the functionality to use gobetween as a load balancer LXDMosaic
can configure to automatically handle routing traffic, but this is a while of yet!

### Stop Deployment

Stopping a deployment is pretty self explanitory, it will stop all the instances
that are linked to this deployment.

### Delete Deployment

Deleting a deployment will delete all containers part of the deployment & the
deployment config itself!
