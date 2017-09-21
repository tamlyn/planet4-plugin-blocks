# Greenpeace Planet 4

This WordPress plugin provides the necessary blocks to be used with Shortcake UI plugin.

**How to create a new block?**

1. Create a new controller class that extends P4BKS_Blocks_Controller inside directory _classes/controller/blocks_. The class name should follow naming convention, for example P4BKS_Blocks_**Blockname**_Controller and its file name should be class-p4bks-blocks-**blockname**-controller.php. 

2. Implement its parent's class two abstract methods. In these 2 methods you need to define the blocks fields and prepare it for rendering.

3. Create the template file that will be used to render your block inside directory _includes/blocks_. If the name of the file is **filename**.php then from within your controller class you can call
$this->view->view_template( '**filename**', $data ); It also works with twig templates. Just add 'twig' as the 3rd argument of the view_template() method.

4. Finally, add the class name to the array that the P4BKS_Loader function takes as an argument in the main plugin file.
