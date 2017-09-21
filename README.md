# Greenpeace Planet 4

This WordPress plugin provides the necessary blocks to be used with Shortcake UI plugin.

**How to create a new block?**

1. Create a new class following naming conventions under directory _classes/controller/blocks/_. For example, P4BKS_Blocks_Xxxxx_Controller and its file should be named class-p4bks-blocks-xxxxx-controller.php. 

2. This class needs to extend P4BKS_Blocks_Controller and utilize its 2 abstract methods. In these 2 methods you need to define the blocks fields and prepare it for rendering.

3. Create the template file that will be used to render your block under directory _includes/blocks/_
If the name of the file is xxxxx.php and from within your block controller you can call
$this->view->view_template( 'xxxxx', $data );

On a sidenote... it also works with twig template. For xxxxx.twig you can call
$this->view->view_template( 'xxxxx', $data, 'twig' );