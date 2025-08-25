<?php

    class My_Mod_Grades_CommentsCommentsModuleFrontController extends ModuleFrontController
    {
        public $product = null;

        public function initContent() {
            parent::initContent();

            $id_product = (int)Tools::getValue('id_product');
            $module_action = Tools::getValue('module_action');
            $actions_list = array(
                'list' => 'initList'
            );
            if ($id_product > 0 && isset($actions_list[$module_action])) {
                $this->product = new Product((int)$id_product, false, $this->context->cookie->id_lang);
                $this->$actions_list[$module_action]();
            }
        }

        protected function initList() {

            $sql = "SELECT * FROM " . _DB_PREFIX_ . "my_mod_comment WHERE id_product = " . $this->product->id . " ORDER BY date_add DESC";
            $comments = Db::getInstance()->executeS($sql);

            $this->context->smarty->assign("comments", $comments);
            $this->context->smarty->assign("product", $this->product);

            $this->setTemplate('list.tpl');
        }
    }