FOR EACH Class file: "X.php" In folder:
libraries/bundle/MagentaCBookModelBundle/src/Entity/Organisation 
(call this: Entity folder) :

    1. Create File
        Create a "XAdmin.php" file in 
        libraries/bundle/MagentaCBookAdminBundle/src/Admin/Organisation 
        (call this folder: Admin folder)

    2. Change File Admin Folder
        In Admin Folder, change Class to: "Class XAdmin extend BaseAdmin"

    3. Create Controller File
        In Admin Folder, Create a new file name: XAdminController.php
    
    4. Change File Controller 
         The file created in step 3, change Class to: "Class XAdminController extends BaseCRUDAdminController"

Do the same for these folder:
    MagentaCBookModelBundle/src/Entity/User
    MagentaCBookModelBundle/src/Entity/Person
    MagentaCBookModelBundle/src/Entity/System




Task 2:
1. Each folder in the Root:
libraries/bundle/MagentaCBookModelBundle/src/Entity

Create the Same Folder in:
libraries/bundle/MagentaCBookAdminBundle/src/Resources/views/Admin

2. For each folder created in Step 1, Create 2 folders: Action, Children

Task 3:

1. In folder:
    libraries/bundle/MagentaCBookAdminBundle/src/Resources/views/
    create Folder CRUD
2. In CRUD, create a template file: list.html.twig.
3. Use the file just created to Override the Listing Template Default.
4. In list.html.twig extend the Default Template File. (@SonataAdmin/CRUD/list.html.twig)


Task 4:
FOR EACH Class file: "XAdmin.php" In folder:
 libraries/bundle/MagentaCBookAdminBundle/src/Admin/Organisation, that's created at the Task 1:

    1. In the path: 
    libraries/bundle/MagentaCBookAdminBundle/src/Resources/views/Admin  
    Create a folder name: X, X is a part of the file's name.     
    
    2. Create a folder name: CRUD in the Folder just created.
    3. Copy file: List.html.twig in task 3 To the CRUD folder in step 2.

Do the same for these folder:
    libraries/bundle/MagentaCBookAdminBundle/src/Admin/User
    libraries/bundle/MagentaCBookAdminBundle/src/Admin/Person
    libraries/bundle/MagentaCBookAdminBundle/src/Admin/System

Task 5:
FOR EACH file: "XAdminController.php" in folder:
libraries/bundle/MagentaCBookAdminBundle/src/Admin/Organisation, that's created at the Task 1:
    Ad Function below:
        "
        public function listAction()
          {
              $this->admin->setTemplate('list', '@MagentaCBookAdmin/Admin/Organisation/{{X}}/CRUD/list.html.twig');
              return parent::listAction();
          }

        "
        
Do the same for these folder:
    libraries/bundle/MagentaCBookAdminBundle/src/Admin/User
    libraries/bundle/MagentaCBookAdminBundle/src/Admin/Person
    libraries/bundle/MagentaCBookAdminBundle/src/Admin/System
