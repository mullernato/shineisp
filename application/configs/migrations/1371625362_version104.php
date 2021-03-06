<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version104 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->changeColumn('admin_user', 'isp_id', 'integer', '4', array(
             'default' => '1',
             ));
        $this->changeColumn('customers', 'isp_id', 'integer', '4', array(
             'default' => '1',
             ));
        $this->changeColumn('customers_groups', 'isp_id', 'integer', '4', array(
             'default' => '1',
             ));
        $this->changeColumn('domains_tlds', 'isp_id', 'integer', '4', array(
             'default' => '1',
             ));
        $this->changeColumn('invoices_settings', 'isp_id', 'integer', '4', array(
             'default' => '1',
             ));
        $this->changeColumn('isp_urls', 'isp_id', 'integer', '4', array(
             'default' => '1',
             ));
        $this->changeColumn('messages', 'isp_id', 'integer', '4', array(
             'default' => '1',
             ));
        $this->changeColumn('orders', 'isp_id', 'integer', '4', array(
             'notnull' => '1',
             'default' => '1',
             ));
        $this->changeColumn('panels', 'isp_id', 'integer', '4', array(
             'notnull' => '1',
             'default' => '1',
             ));
        $this->changeColumn('products', 'isp_id', 'integer', '4', array(
             'default' => '1',
             ));
        $this->changeColumn('products_attributes_groups', 'isp_id', 'integer', '4', array(
             'notnull' => '1',
             'default' => '1',
             ));
        $this->changeColumn('products_categories', 'isp_id', 'integer', '4', array(
             'notnull' => '1',
             'default' => '1',
             ));
        $this->changeColumn('registrars', 'isp_id', 'integer', '4', array(
             'notnull' => '1',
             'default' => '1',
             ));
        $this->changeColumn('servers', 'isp_id', 'integer', '4', array(
             'default' => '1',
             ));
        $this->changeColumn('settings', 'isp_id', 'integer', '4', array(
             'default' => '1',
             ));
        $this->changeColumn('taxes', 'isp_id', 'integer', '4', array(
             'notnull' => '1',
             'default' => '1',
             ));
        $this->changeColumn('tickets_categories', 'isp_id', 'integer', '4', array(
             'notnull' => '1',
             'default' => '1',
             ));
        $this->changeColumn('wiki', 'isp_id', 'integer', '4', array(
             'notnull' => '1',
             'default' => '1',
             ));
    }

    public function down()
    {

    }
}