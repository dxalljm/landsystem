<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "auth_item".
 *
 * @property string $name
 * @property integer $type
 * @property string $description
 * @property string $rule_name
 * @property string $data
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property AuthAssignment[] $authAssignments
 * @property AuthRule $ruleName
 * @property AuthItemChild[] $authItemChildren
 */
class RoleForm extends Item
{
    /**
     * @inheritdoc
     */
	public $child;//这个是用来放权限的
    
    public function init() {

        parent::init();
        //常量TYPE_ROLE=1
        $this->type = \yii\rbac\Item::TYPE_ROLE;

    }
}
