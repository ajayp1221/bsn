<?php
use Migrations\AbstractMigration;

class Intial extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('albumimages');
        $table
            ->addColumn('album_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('image', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('description', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('price', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('status', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('soft_deleted', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('created', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => false,
            ])
            ->create();

        $table = $this->table('albums');
        $table
            ->addColumn('store_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('description', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('status', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('soft_deleted', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('created', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => false,
            ])
            ->create();

        $table = $this->table('albumshares');
        $table
            ->addColumn('contact_no', 'string', [
                'default' => null,
                'limit' => 25,
                'null' => false,
            ])
            ->addColumn('email', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('password', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('type', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('ids', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('bucket_name', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('link', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('expired', 'string', [
                'default' => null,
                'limit' => 25,
                'null' => false,
            ])
            ->addColumn('created', 'string', [
                'default' => null,
                'limit' => 25,
                'null' => false,
            ])
            ->addColumn('status', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('soft_deleted', 'integer', [
                'default' => 0,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('maxviews', 'integer', [
                'default' => 5,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('views', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('view_lock', 'text', [
                'default' => null,
                'limit' => 4294967295,
                'null' => false,
            ])
            ->create();

        $table = $this->table('answers');
        $table
            ->addColumn('customers_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('question_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('option_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('answer_type', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('answer', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('status', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('soft_deleted', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('created', 'string', [
                'default' => null,
                'limit' => 25,
                'null' => true,
            ])
            ->addColumn('is_published', 'boolean', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->create();

        $table = $this->table('brands');
        $table
            ->addColumn('client_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('logo', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('brand_name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('status', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('soft_deleted', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('created', 'integer', [
                'default' => null,
                'limit' => 25,
                'null' => false,
            ])
            ->create();

        $table = $this->table('campaigns');
        $table
            ->addColumn('store_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('campaign_type', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('send_before_day', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('send_date', 'string', [
                'default' => null,
                'limit' => 25,
                'null' => false,
            ])
            ->addColumn('repeat', 'integer', [
                'comment' => '0 for One Time Event and 1 For recursive Event',
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('repeat_type', 'string', [
                'comment' => 'birthday or anniversary',
                'default' => null,
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('compaign_name', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('subject', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('message', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('whos_send', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('status', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('soft_deleted', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('created', 'string', [
                'default' => null,
                'limit' => 25,
                'null' => false,
            ])
            ->addColumn('modified', 'string', [
                'default' => null,
                'limit' => 25,
                'null' => false,
            ])
            ->addColumn('embedcode', 'boolean', [
                'default' => false,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('send_at', 'string', [
                'comment' => 'birthday or anniversary then this column will be used to pick time of msg send job',
                'default' => null,
                'limit' => 10,
                'null' => false,
            ])
            ->addColumn('cost_multiplier', 'integer', [
                'default' => 1,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('totalmsg', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->create();

        $table = $this->table('clients');
        $table
            ->addColumn('senderid', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('plivo_auth_id', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('plivo_auth_token', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('slug', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('email', 'string', [
                'default' => null,
                'limit' => 150,
                'null' => false,
            ])
            ->addColumn('password', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('pwd', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('contact_no', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => false,
            ])
            ->addColumn('image', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('address', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('sms_quantity', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('sms_sent', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('email_left', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('email_sent', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('pass_hash', 'string', [
                'default' => null,
                'limit' => 250,
                'null' => false,
            ])
            ->addColumn('status', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('soft_deleted', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('created', 'integer', [
                'default' => null,
                'limit' => 25,
                'null' => false,
            ])
            ->addColumn('modified', 'integer', [
                'default' => null,
                'limit' => 20,
                'null' => false,
            ])
            ->addColumn('max_device_limit', 'integer', [
                'default' => 1,
                'limit' => 10,
                'null' => false,
            ])
            ->addColumn('low_balance_sms_pause', 'integer', [
                'comment' => 'if this is 1 then non of the low balance alerts will be sent to the client',
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('low_balance_email_pause', 'integer', [
                'comment' => 'if this is 1 then non of the low balance alerts will be sent to the client',
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('low_balance2_sms_pause', 'integer', [
                'comment' => 'if this is 1 then non of the alerts for balance below 100 will be sent to the client',
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('low_balance2_email_pause', 'integer', [
                'comment' => 'if this is 1 then non of the alerts for balance below 100 will be sent to the client',
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->create();

        $table = $this->table('clients_smsplans');
        $table
            ->addColumn('client_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('smsplan_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('created', 'integer', [
                'default' => null,
                'limit' => 25,
                'null' => false,
            ])
            ->create();

        $table = $this->table('configrations_data');
        $table
            ->addColumn('meta_key', 'string', [
                'default' => null,
                'limit' => 200,
                'null' => false,
            ])
            ->addColumn('meta_value', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->create();

        $table = $this->table('customer_visits');
        $table
            ->addColumn('customer_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('came_at', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('visits_till_now', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('store_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->create();

        $table = $this->table('customermeasurements');
        $table
            ->addColumn('customer_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('measurement_type', 'string', [
                'default' => null,
                'limit' => 70,
                'null' => false,
            ])
            ->addColumn('measurement_name', 'string', [
                'default' => null,
                'limit' => 70,
                'null' => false,
            ])
            ->addColumn('value', 'string', [
                'default' => null,
                'limit' => 10,
                'null' => false,
            ])
            ->addColumn('created', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->create();

        $table = $this->table('customers');
        $table
            ->addColumn('store_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('store_name', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => false,
            ])
            ->addColumn('country_code', 'string', [
                'default' => 91,
                'limit' => 30,
                'null' => false,
            ])
            ->addColumn('contact_no', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => false,
            ])
            ->addColumn('email', 'string', [
                'default' => null,
                'limit' => 150,
                'null' => false,
            ])
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('gender', 'integer', [
                'default' => 2,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('dob', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => false,
            ])
            ->addColumn('doa', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => false,
            ])
            ->addColumn('added_by', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => false,
            ])
            ->addColumn('opt_in', 'boolean', [
                'comment' => '1 for opted',
                'default' => true,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('status', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('soft_deleted', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('created', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => false,
            ])
            ->addColumn('modified', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => false,
            ])
            ->addColumn('age', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('refered_by', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('spouse_name', 'string', [
                'default' => null,
                'limit' => 200,
                'null' => false,
            ])
            ->addColumn('spouse_date', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('kids_info', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('is_sent', 'integer', [
                'default' => 1,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('tmpcol', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('tags', 'string', [
                'default' => '',
                'limit' => 50,
                'null' => false,
            ])
            ->addIndex(
                [
                    'contact_no',
                ],
                ['type' => 'fulltext']
            )
            ->create();

        $table = $this->table('devices');
        $table
            ->addColumn('client_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('device_uuid', 'string', [
                'default' => null,
                'limit' => 200,
                'null' => false,
            ])
            ->addColumn('status', 'integer', [
                'default' => 0,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('added_on', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => false,
            ])
            ->create();

        $table = $this->table('excludes');
        $table
            ->addColumn('model_name', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('value', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('status', 'integer', [
                'default' => null,
                'limit' => 1,
                'null' => false,
            ])
            ->addColumn('soft_deleted', 'integer', [
                'default' => null,
                'limit' => 1,
                'null' => false,
            ])
            ->addColumn('created', 'integer', [
                'default' => null,
                'limit' => 20,
                'null' => false,
            ])
            ->addColumn('modified', 'integer', [
                'default' => null,
                'limit' => 20,
                'null' => false,
            ])
            ->create();

        $table = $this->table('gshuplogs');
        $table
            ->addColumn('external_id', 'string', [
                'default' => null,
                'limit' => 200,
                'null' => true,
            ])
            ->addColumn('status', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'string', [
                'default' => null,
                'limit' => 200,
                'null' => true,
            ])
            ->addColumn('created', 'string', [
                'default' => null,
                'limit' => 200,
                'null' => true,
            ])
            ->addColumn('extras', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->create();

        $table = $this->table('loyaltyledger');
        $table
            ->addColumn('loyaltymember_id', 'integer', [
                'default' => null,
                'limit' => 9,
                'null' => false,
            ])
            ->addColumn('points_credit', 'integer', [
                'comment' => 'Add Points',
                'default' => null,
                'limit' => 9,
                'null' => false,
            ])
            ->addColumn('points_debit', 'integer', [
                'comment' => 'Redeem Points',
                'default' => null,
                'limit' => 9,
                'null' => false,
            ])
            ->addColumn('points_balance', 'integer', [
                'comment' => 'BL = pBL + Cr - Dr',
                'default' => null,
                'limit' => 9,
                'null' => false,
            ])
            ->addColumn('comments', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('dated', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('ratio', 'decimal', [
                'default' => null,
                'limit' => 10,
                'null' => false,
            ])
            ->addColumn('adjustment', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->create();

        $table = $this->table('loyaltymembers');
        $table
            ->addColumn('customer_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('store_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('total_points', 'integer', [
                'default' => null,
                'limit' => 9,
                'null' => false,
            ])
            ->addColumn('points_left', 'integer', [
                'default' => null,
                'limit' => 9,
                'null' => false,
            ])
            ->addColumn('points_used', 'integer', [
                'default' => null,
                'limit' => 9,
                'null' => false,
            ])
            ->addColumn('ratio', 'decimal', [
                'default' => null,
                'limit' => 10,
                'null' => false,
            ])
            ->addColumn('created', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('status', 'boolean', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->create();

        $table = $this->table('mall_customerbills');
        $table
            ->addColumn('customer_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('bill_file', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('anount', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => false,
            ])
            ->addColumn('status', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('created', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->create();

        $table = $this->table('mall_shop');
        $table
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('status', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('created', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('modified', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->create();

        $table = $this->table('mall_verificationcounters');
        $table
            ->addColumn('store_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('email', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('password', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('pwd', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('created', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('status', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->create();

        $table = $this->table('mall_vouchercounters');
        $table
            ->addColumn('store_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('email', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('password', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('pwd', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('created', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('status', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->create();

        $table = $this->table('mall_vouchers');
        $table
            ->addColumn('shop_name', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('voucher_code', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('status', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('created', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('modified', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->create();

        $table = $this->table('messages');
        $table
            ->addColumn('customer_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('store_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('campaign_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('promo_code', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('used', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('used_date', 'integer', [
                'default' => null,
                'limit' => 25,
                'null' => false,
            ])
            ->addColumn('status', 'integer', [
                'comment' => '0 if cron sent the msg 1 if pending to send',
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('cause', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => true,
            ])
            ->addColumn('received', 'integer', [
                'comment' => '1 if received',
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('open', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => false,
            ])
            ->addColumn('api_key', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('api_response', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('soft_deleted', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('created', 'string', [
                'default' => null,
                'limit' => 25,
                'null' => false,
            ])
            ->addColumn('cost_multiplier', 'integer', [
                'default' => 1,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('external_msgid', 'string', [
                'default' => null,
                'limit' => 200,
                'null' => true,
            ])
            ->create();

        $table = $this->table('notifications');
        $table
            ->addColumn('store_slug', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('type', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('device_token', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('deviceid', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('view', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('status', 'integer', [
                'default' => 0,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('soft_deleted', 'integer', [
                'default' => 0,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('created', 'integer', [
                'default' => null,
                'limit' => 15,
                'null' => false,
            ])
            ->addColumn('modified', 'integer', [
                'default' => null,
                'limit' => 15,
                'null' => false,
            ])
            ->create();

        $table = $this->table('options');
        $table
            ->addColumn('question_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('option', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('status', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('soft_deleted', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('created', 'integer', [
                'default' => null,
                'limit' => 25,
                'null' => false,
            ])
            ->create();

        $table = $this->table('productcats');
        $table
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 200,
                'null' => false,
            ])
            ->addColumn('store_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('gender', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => false,
            ])
            ->create();

        $table = $this->table('products');
        $table
            ->addColumn('store_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('category', 'string', [
                'default' => null,
                'limit' => 250,
                'null' => false,
            ])
            ->addColumn('product_name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('purchase_count', 'string', [
                'default' => null,
                'limit' => 10,
                'null' => false,
            ])
            ->addColumn('productcat_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->create();

        $table = $this->table('purchases');
        $table
            ->addColumn('store_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('product_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('customer_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('product_name', 'string', [
                'default' => null,
                'limit' => 200,
                'null' => false,
            ])
            ->addColumn('qty', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('price', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('sold_on', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => false,
            ])
            ->create();

        $table = $this->table('pushmessages');
        $table
            ->addColumn('store_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('store_slug', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('at_time', 'string', [
                'default' => null,
                'limit' => 30,
                'null' => false,
            ])
            ->addColumn('image', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('title', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('message', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('status', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('soft_deleted', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('created', 'integer', [
                'default' => null,
                'limit' => 15,
                'null' => false,
            ])
            ->addColumn('modified', 'integer', [
                'default' => null,
                'limit' => 15,
                'null' => false,
            ])
            ->create();

        $table = $this->table('questions');
        $table
            ->addColumn('store_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('question', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('question_type', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('status', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('soft_deleted', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('created', 'integer', [
                'default' => null,
                'limit' => 25,
                'null' => false,
            ])
            ->addColumn('view_type', 'string', [
                'default' => null,
                'limit' => 40,
                'null' => false,
            ])
            ->addColumn('no_delete', 'boolean', [
                'default' => false,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('no_skip', 'boolean', [
                'default' => false,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('order_seq', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('buyer', 'string', [
                'comment' => 'N = Non Buyer, B = Buyer, BN = Both Non Buyer & Buyer',
                'default' => null,
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('group_mark', 'string', [
                'default' => null,
                'limit' => 1,
                'null' => false,
            ])
            ->create();

        $table = $this->table('recommend_screen');
        $table
            ->addColumn('store_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('active', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('header_text', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('description', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('embedcode', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('created', 'string', [
                'default' => null,
                'limit' => 200,
                'null' => false,
            ])
            ->create();

        $table = $this->table('share_screen');
        $table
            ->addColumn('store_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('active', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('header_text', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('description', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('created', 'string', [
                'default' => null,
                'limit' => 200,
                'null' => false,
            ])
            ->addColumn('popup_title', 'string', [
                'default' => null,
                'limit' => 250,
                'null' => false,
            ])
            ->addColumn('popup_message', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('popup_url', 'string', [
                'default' => null,
                'limit' => 400,
                'null' => false,
            ])
            ->addColumn('embedcode', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->create();

        $table = $this->table('sharedcode_redeemed');
        $table
            ->addColumn('customer_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('sharedcode_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('redeemed_at', 'string', [
                'default' => null,
                'limit' => 200,
                'null' => false,
            ])
            ->create();

        $table = $this->table('sharedcodes');
        $table
            ->addColumn('client_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('customer_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('code', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('type', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('store_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('redeemed_count', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => false,
            ])
            ->addColumn('created_at', 'string', [
                'default' => null,
                'limit' => 200,
                'null' => false,
            ])
            ->create();

        $table = $this->table('smsledger');
        $table
            ->addColumn('client_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('store_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('customer_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('comments', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('credit', 'integer', [
                'default' => null,
                'limit' => 9,
                'null' => false,
            ])
            ->addColumn('debit', 'integer', [
                'default' => null,
                'limit' => 9,
                'null' => false,
            ])
            ->addColumn('balance', 'integer', [
                'default' => null,
                'limit' => 9,
                'null' => false,
            ])
            ->addColumn('extra', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('created', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => false,
            ])
            ->addColumn('number', 'string', [
                'default' => null,
                'limit' => 15,
                'null' => true,
            ])
            ->create();

        $table = $this->table('smsplans');
        $table
            ->addColumn('title', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('description', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('sms', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('email', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('price', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('status', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('soft_deleted', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('created', 'integer', [
                'default' => null,
                'limit' => 25,
                'null' => false,
            ])
            ->create();

        $table = $this->table('social_connections');
        $table
            ->addColumn('client_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('model', 'string', [
                'default' => null,
                'limit' => 30,
                'null' => false,
            ])
            ->addColumn('key', 'string', [
                'default' => null,
                'limit' => 400,
                'null' => false,
            ])
            ->addColumn('secret', 'string', [
                'default' => null,
                'limit' => 400,
                'null' => false,
            ])
            ->addColumn('access_token', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('raw_data', 'text', [
                'default' => null,
                'limit' => 4294967295,
                'null' => false,
            ])
            ->addColumn('email', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('last_accessed', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('status', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->create();

        $table = $this->table('socialshares');
        $table
            ->addColumn('client_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('image', 'string', [
                'default' => null,
                'limit' => 500,
                'null' => false,
            ])
            ->addColumn('description', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('facebook', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('twitter', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('instagram', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('status', 'integer', [
                'comment' => '0 if not posted and 1 if posted',
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('soft_deleted', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('created', 'integer', [
                'default' => null,
                'limit' => 25,
                'null' => false,
            ])
            ->addColumn('schedule_date', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => false,
            ])
            ->create();

        $table = $this->table('store_settings');
        $table
            ->addColumn('store_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('meta_key', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('meta_value', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => false,
            ])
            ->create();

        $table = $this->table('stores');
        $table
            ->addColumn('zkpstoreid', 'integer', [
                'default' => 0,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('brand_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('slug', 'string', [
                'default' => null,
                'limit' => 500,
                'null' => false,
            ])
            ->addColumn('address', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('status', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('soft_deleted', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('created', 'integer', [
                'default' => null,
                'limit' => 25,
                'null' => false,
            ])
            ->addColumn('contact_numbers', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('emails', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('links', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('timings', 'string', [
                'default' => null,
                'limit' => 200,
                'null' => false,
            ])
            ->addColumn('closed', 'string', [
                'default' => null,
                'limit' => 200,
                'null' => false,
            ])
            ->addColumn('location_cordinates', 'string', [
                'default' => null,
                'limit' => 200,
                'null' => false,
            ])
            ->addColumn('discount_on_next_visit', 'boolean', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('unsub_gshup_keyword', 'string', [
                'comment' => 'Keyword that is sent by customer to send it in unsubscribe msg',
                'default' => null,
                'limit' => 50,
                'null' => false,
            ])
            ->create();

        $table = $this->table('templatemessages');
        $table
            ->addColumn('store_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('type', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('message', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('status', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('soft_deleted', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('created', 'string', [
                'default' => null,
                'limit' => 25,
                'null' => false,
            ])
            ->create();

        $table = $this->table('users');
        $table
            ->addColumn('email', 'string', [
                'default' => null,
                'limit' => 150,
                'null' => false,
            ])
            ->addColumn('password', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('first_name', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('last_name', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('gender', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('roles', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => false,
            ])
            ->addColumn('status', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('soft_deleted', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('created', 'integer', [
                'default' => null,
                'limit' => 25,
                'null' => false,
            ])
            ->create();

        $table = $this->table('welcomemsgs');
        $table
            ->addColumn('customer_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('type', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => false,
            ])
            ->addColumn('created', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('store_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('cost_multiplier', 'integer', [
                'default' => 1,
                'limit' => 11,
                'null' => false,
            ])
            ->create();

    }

    public function down()
    {
        $this->dropTable('albumimages');
        $this->dropTable('albums');
        $this->dropTable('albumshares');
        $this->dropTable('answers');
        $this->dropTable('brands');
        $this->dropTable('campaigns');
        $this->dropTable('clients');
        $this->dropTable('clients_smsplans');
        $this->dropTable('configrations_data');
        $this->dropTable('customer_visits');
        $this->dropTable('customermeasurements');
        $this->dropTable('customers');
        $this->dropTable('devices');
        $this->dropTable('excludes');
        $this->dropTable('gshuplogs');
        $this->dropTable('loyaltyledger');
        $this->dropTable('loyaltymembers');
        $this->dropTable('mall_customerbills');
        $this->dropTable('mall_shop');
        $this->dropTable('mall_verificationcounters');
        $this->dropTable('mall_vouchercounters');
        $this->dropTable('mall_vouchers');
        $this->dropTable('messages');
        $this->dropTable('notifications');
        $this->dropTable('options');
        $this->dropTable('productcats');
        $this->dropTable('products');
        $this->dropTable('purchases');
        $this->dropTable('pushmessages');
        $this->dropTable('questions');
        $this->dropTable('recommend_screen');
        $this->dropTable('share_screen');
        $this->dropTable('sharedcode_redeemed');
        $this->dropTable('sharedcodes');
        $this->dropTable('smsledger');
        $this->dropTable('smsplans');
        $this->dropTable('social_connections');
        $this->dropTable('socialshares');
        $this->dropTable('store_settings');
        $this->dropTable('stores');
        $this->dropTable('templatemessages');
        $this->dropTable('users');
        $this->dropTable('welcomemsgs');
    }
}
