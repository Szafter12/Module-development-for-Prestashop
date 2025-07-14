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
        $this->version = '1.0.0';
        $this->author = 'JakubPachut';
        $this->need_instance = 0;

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('My module for comments and grades');
        $this->description = $this->l('You can add a comments and grades for your products ');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall this module');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        parent::install();
        $this->registerHook('displayProductTabContent');
        return true;
    }

    public function uninstall()
    {
        return parent::uninstall();
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
        }
    }

    protected function assignProductTabContent() {
        $enable_grades = Configuration::get('MY_MOD_ENABLE_GRADES');
        $enable_comments = Configuration::get('MY_MOD_ENABLE_COMMENTS');

        $id_product = Tools::getValue('id_product');
        $comment = Tools::getValue('comment');
        $comment = Tools::getValue('grade');
    }
}
