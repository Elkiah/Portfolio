<?php
class BetterBuilderPlugin
{

    public function __construct()
    {
        if ( ! function_exists( 'add_action' )) {
            echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
            exit;
        }
    }

    public function getPluginName() {
        return $this->name;
    }
    
    public function setup()
    {
        //add_filter( 'admin_footer_text', [$this, 'tr_remove_footer_admin']);
        $settings = [
            'view_file' => __DIR__ . '/page.php',
            'menu' => 'Better Builder'
        ];
        (new \TypeRocket\Register\Page('TypeRocket', __('Better Builder'), __('Better Builder Options'), $settings))
            ->addToRegistry()->setIcon('betterbuilder');
    }

    public function tr_remove_footer_admin()
    {
        echo __('TypeRocket developer mode! Run time is ') . (TR_END - TR_START);
    }

}

add_action( 'typerocket_loaded', [new BetterBuilderPlugin(), 'setup']);