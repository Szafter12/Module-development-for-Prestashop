<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class My_mod_grades_comments extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'my_mod_grades_comments';
        $this->tab = 'front_office_features';
        $this->version = '1.0.1';
        $this->author = 'JakubPachut';
        $this->need_instance = 0;

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('My module for comments and grades');
        $this->description = $this->l('You can add a comments and grades for your products');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall this module');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        if (!parent::install())
            return false;

        $sql_file = dirname(__FILE__) . '/install/install.sql';
        if (!$this->loadSQLFile($sql_file))
            return false;

        if (!$this->registerHook('displayProductTabContent'))
            return false;

        Configuration::updateValue('MYMOD_GRADES', '1');
        Configuration::updateValue('MYMOD_COMMENTS', '1');

        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall())
            return false;

        $sql_file = dirname(__FILE__) . '/install/uninstall.sql';
        if (!$this->loadSQLFile($sql_file))
            return false;

        Configuration::deleteByName('MYMOD_GRADES');
        Configuration::deleteByName('MYMOD_COMMENTS');
        return true;
    }

    public function loadSQLFile($sql_file)
    {
        $sql_content = file_get_contents($sql_file);
        $sql_content = str_replace('PREFIX_', _DB_PREFIX_, $sql_content);
        $sql_requests = preg_split("/;\s*[\r\n]+/", $sql_content);

        foreach ($sql_requests as $request) {
            if (!empty($request) && !Db::getInstance()->execute(trim($request))) {
                return false;
            }
        }
        return true;
    }

    public function getContent()
    {
        $this->processConfiguration();
        if (Configuration::get('MY_MOD_ENABLE_GRADES') !== null && Configuration::get('MY_MOD_ENABLE_COMMENTS') !== null) {
            $this->assignConfiguration();
        }
        return $this->display(__FILE__, 'getContent.tpl');
    }

    protected function processConfiguration()
    {
        if (Tools::isSubmit('mymod_pc_form')) {
            $comments = Tools::getValue('enable_comments');
            $grades = Tools::getValue('enable_grades');

            Configuration::updateValue('MY_MOD_ENABLE_GRADES', $grades);
            Configuration::updateValue('MY_MOD_ENABLE_COMMENTS', $comments);

            $this->context->smarty->assign('confiramtion', 'ok');
        }
    }

    protected function assignConfiguration()
    {
        $enable_grades = Configuration::get('MY_MOD_ENABLE_GRADES');
        $enbale_comments = Configuration::get('MY_MOD_ENABLE_COMMENTS');

        $this->context->smarty->assign('enable_grades', $enable_grades);
        $this->context->smarty->assign('enable_comments', $enbale_comments);
    }

    public function hookDisplayProductTabContent($params)
    {
        $this->processProductTabContent();
        $this->assignProductTabContent();
        return $this->display(__FILE__, 'displayProductTabContent.tpl');
    }

    protected function processProductTabContent()
    {
        if (Tools::isSubmit('mymod_pc_submit_comment')) {
            $id_product = Tools::getValue('id_product');
            $grade = Tools::getValue('grade');
            $comment = Tools::getValue('comment');
            $insert = [
                'id_product' => $id_product,
                'grade' => (int)$grade,
                'comment' => pSQL($comment),
                'date_add' => date('Y-m-d H:i:s')
            ];

            Db::getInstance()->insert('my_mod_comment', $insert);

            $id_product = (int)Tools::getValue('id_product');
            $product = new Product($id_product, true, $this->context->language->id);

            $link = $this->context->link->getProductLink($product);
            $link .= '?new_comment=1';

            Tools::redirect($link);
        }
    }

    protected function assignProductTabContent()
    {
        $enable_grades = Configuration::get('MY_MOD_ENABLE_GRADES');
        $enable_comments = Configuration::get('MY_MOD_ENABLE_COMMENTS');
        $new_comment_posted = null;
        if (Tools::getValue('new_comment') !== null) {
            $new_comment_posted = Tools::getValue('new_comment') == 1;
        }

        $id_product = Tools::getValue('id_product');
        $comments = Db::getInstance()->executeS('SELECT * FROM ' . _DB_PREFIX_ . 'my_mod_comment WHERE id_product = ' . (int)$id_product . ' ORDER BY date_add DESC LIMIT 3');

        $this->context->controller->addCSS($this->_path . "views/css/mymodcomment.css", 'all');
        $this->context->controller->addJS($this->_path . "views/js/mymodcomment.js");

        $this->context->smarty->assign("new_comment_posted", $new_comment_posted);
        $this->context->smarty->assign("enable_grades", $enable_grades);
        $this->context->smarty->assign("enable_comments", $enable_comments);
        $this->context->smarty->assign("comments", $comments);
    }
}
