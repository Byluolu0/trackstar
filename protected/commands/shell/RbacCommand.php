<?php 
class RbacCommand extends CConsoleCommand
{
    private $_authManager;
 
    public function getHelp()
    {
        return <<<EOD
USAGE
  rbac
DESCRIPTION
  This command generates an initial RBAC authorization hierarchy.
EOD;
    }
 
    /**
     * Execute the action.
     * @param array command line parameters specific for this command
     */
    public function run($args)
    {
        //ensure that an authManager is defined as this is mandatory for creating an auth heirarchy
        if(($this->_authManager=Yii::app()->authManager)===null)
        {
            echo "Error: an authorization manager, named 'authManager' must be configured to use this command.\n";
            echo "If you already added 'authManager' component in application configuration,\n";
            echo "please quit and re-enter the yiic shell.\n";
            return;
        }
 
        //provide the opportunity for the use to abort the request
        echo "This command will create three roles: Owner, Member, and Reader and the following premissions:\n";
        echo "create, read, update and delete user\n"; echo "create, read, update and delete project\n";
        echo "create, read, update and delete issue\n"; echo "Would you like to continue? [Yes|No] ";
 
        //check the input from the user and continue if they indicated yes to the above question
        if(!strncasecmp(trim(fgets(STDIN)),'y',1))
        {
            
            
            //create a general task-level permission for admins 
            $this->_authManager->createTask("adminManagement", "access to the application administration functionality"); 
            //create the site admin role, and add the appropriate permissions
            $role=$this->_authManager->createRole("admin"); 
            $role->addChild("owner"); 
            $role->addChild("reader"); 
            $role->addChild("member"); 
            $role->addChild("adminManagement");
            //ensure we have one admin in the system (force it to be user id #1) 
            $this->_authManager->assign("admin",1);
 
            //provide a message indicating success 
            echo "Authorization hierarchy successfully generated.";
        }
    }
}