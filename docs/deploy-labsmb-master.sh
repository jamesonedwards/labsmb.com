#!/bin/bash
### LOCAL ###
#ROOT_DEPLOY_PATH="/cygdrive/c/Dev/labsmb.com/deploy/"
#TMP_DIR="tmp"
#TMP_DEPLOY_PATH=$ROOT_DEPLOY_PATH$TMP_DIR"/"
#GIT_REPO_URL="https://xxx:xxx@bitbucket.org/mcgarrybowen/labsmb.com.git"
#DEST_PATH="/cygdrive/c/Dev/labsmb.com/live/"

### PRODUCTION ###
ROOT_DEPLOY_PATH="/opt/bitnami/apps/labsmb.com/deploy/"
TMP_DIR="tmp"
TMP_DEPLOY_PATH=$ROOT_DEPLOY_PATH$TMP_DIR"/"
GIT_REPO_URL="https://labsmb_deployments:lAbsmb123@bitbucket.org/mcgarrybowen/labsmb.com.git"
DEST_PATH="/opt/bitnami/apps/labsmb.com/htdocs/"

echo "Are you sure you want to deploy this code?"
select yn in "Yes" "No"; do
	case $yn in
		Yes )
			echo "Deployment script started with the following parameters:";
			echo "ROOT_DEPLOY_PATH: "$ROOT_DEPLOY_PATH;
			echo "TMP_DEPLOY_PATH: "$TMP_DEPLOY_PATH;
			echo "DEST_PATH: "$DEST_PATH;
			echo "GIT_REPO_URL: "$GIT_REPO_URL;
			
			echo "Start with emply deploy directory...";
			rm -rf $ROOT_DEPLOY_PATH*;
			
			echo "Change to deploy directory...";
			cd $ROOT_DEPLOY_PATH;
			
			echo "Clone the repo...";
			git clone $GIT_REPO_URL $TMP_DIR;
			
			echo "Create the correct config files and delete the unused ones...";
			mv $TMP_DEPLOY_PATH"www/application/config/custom_config-PROD.php" $TMP_DEPLOY_PATH"www/application/config/custom_config.php";
			mv $TMP_DEPLOY_PATH"www/application/config/database-PROD.php" $TMP_DEPLOY_PATH"www/application/config/database.php";
			rm $TMP_DEPLOY_PATH"www/application/config/custom_config-DEV.php";
			rm $TMP_DEPLOY_PATH"www/application/config/database-DEV.php";
			
			echo "Rsync the contents of the deployment subdirectory to the live project root, excluding uploaded images directory...";
			rsync -avz --delete --exclude="images/projects/" $TMP_DEPLOY_PATH"www/" $DEST_PATH;

			echo "Clear the CI cache...";
			rm -rf $DEST_PATH"application/cache/*";
			
			echo "Make sure the permissions are set up correctly...";
			chmod -R 755 $DEST_PATH;
			chmod -R 777 $DEST_PATH"images/projects";
			chmod -R 777 $DEST_PATH"application/cache";
			chmod -R 777 $DEST_PATH"application/logs";
			
			echo "Clean up deploy directory...";
			rm -rf $ROOT_DEPLOY_PATH*;
			
			echo "Deployment finished. Exiting..."; exit;;
		No ) echo "No deployment performed. Exiting..."; exit;;
	esac
done
