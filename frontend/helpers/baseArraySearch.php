<?php
namespace frontend\helpers;
use app\models\Farms;
use app\models\ManagementArea;
class baseArraySearch
{
	private $data = [];
	private $where;
	private $temp = [];
	private $namelist;
	private $echartsWhere = [];
	private $field;
	public function __construct($data)
	{
// 		var_dump($data->getModels());exit;
		if(!empty($data->getModels()))
			$this->data = $data->getModels();	
		else 
			return null;
		$this->temp = [];
		$this->where = $this->whereToStr($data->query->where);
		return $this;
	}
	public function whereToStr($condition)
	{
		if (!is_array($condition)) {
			return $condition;
		}
		
		if (!isset($condition[0])) {
			// hash format: 'column1' => 'value1', 'column2' => 'value2', ...
			foreach ($condition as $name => $value) {
				if ($this->isEmpty($value)) {
					unset($condition[$name]);
				}
			}
			return $condition;
		}
		$operator = array_shift($condition);
        switch (strtoupper($operator)) {
            case 'NOT':
            case 'AND':
            case 'OR':
                foreach ($condition as $i => $operand) {
                	$subcondition = $this->whereToStr($operand);
                    if ($this->isEmpty($subcondition)) {
                        unset($condition[$i]);
                    } else {
                        $condition[$i] = $subcondition;
                    }
                }
              	break;
            case 'LIKE':
            	return [$condition[0]=>$condition[1]];
            	break;
            default:
            	$condition = null;
        }
       	return $condition;
	}
	protected function isEmpty($value)
	{
		return $value === '' || $value === [] || $value === null || is_string($value) && trim($value) === '';
	}
	protected function isOther($value)
	{
// 		var_dump($value);
// 		echo '-----------------';
// 		exit;
		if(is_array($value)) {
			if(array_key_exists(1, $value)) {
				if(strtoupper($value[0]) == 'BETWEEN' or strtoupper($value[0] == 'NOT BETWEEN'))
					return true;
				
			} else 
				return false;
		} else 
			return false;
	}
	
	public function search()
	{
// 		var_dump($this->where);exit;
		if($this->where) {
			foreach ($this->where as $optionvalue) {
				if(is_array($optionvalue)) {
					foreach ($optionvalue as $okey=>$oval) {
// 						var_dump($optionvalue);exit;
						foreach ($this->data as $key => $value) {
							if($value->getAttribute($okey) == (int)$oval) {
// 								var_dump($oval);
	// 							echo $value->getAttribute($okey).'-----'.$oval.'<br>';
								$this->temp[] = $this->data[$key];
							}
						}
				}
				}
			}
		}
		return $this;
	}
	
	public function all()
	{
		if($this->temp)
			return $this->temp;
		else 
			return $this->data;
	}
	
	public function one()
	{
		return $this->data;
	}
	
	public function sum($field,$num = 1)
	{
// 		var_dump($field);exit;s
		$sum = 0.0;
		if($this->temp)
			$data = $this->temp;
		else
			$data = $this->data;
		if(is_array($field)) {
			$operator = array_shift($field);
			
			switch (strtoupper($operator)) {
				case '+':
					foreach ($data as $value) {
					
						if(is_array($field[1])) {
							$model = 'app\\models\\'.$field[1][0];
							// 							var_dump($field);exit;
							$fieldValue = $model::find()->where(['id'=>$value->getAttribute($field[1][1][0])])->one()[$field[1][1][1]];
						}
						$sum += bcadd($value->getAttribute($field[0]),$fieldValue);
					}
					break;
				case '-':
					foreach ($data as $value) {
					
						if(is_array($field[1])) {
							$model = 'app\\models\\'.$field[1][0];
							// 							var_dump($field);exit;
							$fieldValue = $model::find()->where(['id'=>$value->getAttribute($field[1][1][0])])->one()[$field[1][1][1]];
						}
						$sum += bcsub($value->getAttribute($field[0]),$fieldValue);
					}
					break;
				case '*':
// 					var_dump($field);exit;
					
					if($this->echartsWhere) {
						
						$keys = array_keys($this->echartsWhere);
						$values = array_values($this->echartsWhere);
// 						var_dump($keys);var_dump($field);exit;
						foreach ($data as $value) {
							if($value->getAttribute($keys[0]) == $values[0] and $value->getAttribute($keys[1]) == $values[1]) {
								if(is_array($field[1])) {
									$model = 'app\\models\\'.$field[1][0];
									$fieldValue = $model::find()->where(['id'=>$value->getAttribute($field[1][1][0])])->one()[$field[1][1][1]];
								}
								$sum += bcmul($value->getAttribute($field[0]),$fieldValue);
							}
						}
					} else {
						
						foreach ($data as $value) {
							if(isset($field[0]) and is_array($field[0])) {
								$sum += bcmul($value->getAttribute($field[0][0]),$value->getAttribute($field[0][1]));
							}
							if(isset($field[1]) and is_array($field[1])) {
								$model = 'app\\models\\'.$field[1][0];;
								$fieldValue = $model::find()->where(['id'=>$value->getAttribute($field[1][1][0])])->one()[$field[1][1][1]];
								$sum += bcmul($value->getAttribute($field[0]),$fieldValue);
							}
						}
					}
					break;
				case '/':
					foreach ($data as $value) {
						
						if(is_array($field[1])) {
							$model = 'app\\models\\'.$field[1][0];
// 							var_dump($field);exit;
							$fieldValue = $model::find()->where(['id'=>$value->getAttribute($field[1][1][0])])->one()[$field[1][1][1]];
						}
						$sum += bcdiv($value->getAttribute($field[0]),$fieldValue);
					}
					break;
			}
// 			return sprintf("%.2f", $sum/$num);
		} else {
			$strArr = '';
// 			var_dump($this->echartsWhere);
			if($this->echartsWhere) {
				$keys = array_keys($this->echartsWhere);
				$values = array_values($this->echartsWhere);
// 				var_dump($values);
				foreach ($data as $value) {
					if($value->getAttribute($keys[0]) == $values[0] and $value->getAttribute($keys[1]) == $values[1]) {
						$sum += $value->getAttribute($field);
					}	
				}
			} else {
				var_dump($field);exit;
				foreach ($data as $key => $value) {
					if(is_numeric($value->getAttribute($field))) {
						$sum += $value->getAttribute($field);
					}
				}
				
			}
		}
		if($sum)
			return sprintf("%.2f", $sum/$num);
		else
			return 0;
	}
	public function count($field = NULL,$state = TRUE)
	{
		$newdata = [];
		$olddata = [];
// 		if($this->temp)
// 			$data = $this->temp;
// 		else
// 			$data = $this->data;
		if(empty($this->data))
			return 0;
		if(empty($field))
			return count($this->data);
		else {
			if($field == 'farmer_id' and $state) {
				$farm = [];
				$farmid = [];
// 				var_dump($this->temp);
				foreach ($this->data as $key => $value) {
// 					var_dump($value->getAttribute('farms_id'));
					if(\Yii::$app->controller->id == 'farms')
						$farmid[] = $value->getAttribute('id');
					else
						$farmid[] = $value->getAttribute('farms_id');
				}
// 				var_dump($farmid);exit;
				$allid = array_unique($farmid);
				$farm = Farms::find()->where(['id'=>$allid])->all();
				foreach ($farm as $k => $v) {
					$olddata[] = ['name'=>$v['farmername'],'cardid'=>$v['cardid']];
				}
// 				var_dump($farmid);exit;
				$newdata = $this->unique_arr($olddata);
				return count($newdata);
			} else {
				
				foreach ($this->data as $key => $value) {
					if(!empty($value->getAttribute($field)) and $value->getAttribute($field) !== 0 and $value->getAttribute($field) !== '')
						$olddata[] = ['id'=>$value->getAttribute($field)];
				}
// 				var_dump($olddata);exit;
				if($state) {
					if($olddata) {
						$newdata = $this->unique_arr($olddata);
						return count($newdata);
					} else 
						return 0;
				} else 
					return count($data);
			}
		}
	}
	
	public function getName($model,$getfield,$field)
	{
		$this->field = $field;
		$modelclass = 'app\\models\\'.$model;
		$data = [];
		$result = [];
		$newdata = [];
		foreach ($this->data as $key => $value) {
			if($value->getAttribute($field) !== '' and $value->getAttribute($field) !== 0)
			$data[] = $value->getAttribute($field);
		}

		$newdata = array_unique($data);
		if($newdata) {
			$nameArray = $modelclass::find()->where(['id'=>$newdata])->all();
			foreach ($nameArray as $value) {
				$result[$value['id']] = $value[$getfield];
			}
		}
		$this->namelist = $result;
// 		var_dump($result);
		return $this;
	}
	
	public function typenameList()
	{
		$result = [];
		if(is_array($this->namelist)) {
			foreach ($this->namelist as $value) {
				$result[] = $value;
			}
		}
		return $result;
	}
	public function getOne($id)
	{
// 		var_dump($id);exit;
		if($id == 0)
			return NULL;
		else 
			return $this->namelist[$id];
	}
	public function getList()
	{
		return $this->namelist;
	}
	
	public function getEchartsData($field,$num = 1)
	{
		if($this->temp)
			$data = $this->temp;
		else
			$data = $this->data;
		
// 		var_dump($this->namelist);
		if(isset($this->where[0]['management_area'])) 
			$management_area = [$this->where[0]['management_area']];
		else
			$management_area = [1,2,3,4,5,6,7];
		foreach ($management_area as $areaid) {
			$sum = [];
			foreach ($this->namelist as $key => $list) {
// 				var_dump($key);exit;
				$sum[] = $this->where(['management_area'=>$areaid,$this->field=>$key])->sum($field,$num);
// 				var_dump($sum);
			}
		
			$result[] = [
					'name' => str_ireplace('管理区', '', ManagementArea::find()->where(['id'=>$areaid])->one()['areaname']),
					'type' => 'bar',
					'stack' => $areaid,
					'data' => $sum
			];
		}
// 		var_dump($result);
		return json_encode($result);
	}
	
	public function where($array = NULL)
	{
		if(empty($array))
			return $this->echartsWhere;
		else {
			$this->echartsWhere = $array;
		}
		
		return $this;
	}
	
	public function unique_arr($array2D, $stkeep = false, $ndformat = true) {
		// 判断是否保留一级数组键 (一级数组键可以为非数字)
		if ($stkeep)
			$stArr = array_keys ( $array2D );
			
// 		var_dump($array2D);exit;
		// 判断是否保留二级数组键 (所有二级数组键必须相同)
		if ($ndformat)
			$ndArr = array_keys(end($array2D));
			
		// 降维,也可以用implode,将一维数组转换为用逗号连接的字符串
		foreach ( $array2D as $v ) {
			$v = join ( ",", $v );
			$temp [] = $v;
		}
	
		// 去掉重复的字符串,也就是重复的一维数组
		$temp = array_unique ( $temp );
	
		// 再将拆开的数组重新组装
		foreach ( $temp as $k => $v ) {
			if ($stkeep)
				$k = $stArr [$k];
			if ($ndformat) {
				$tempArr = explode ( ",", $v );
				foreach ( $tempArr as $ndkey => $ndval )
					$output [$k] [$ndArr [$ndkey]] = $ndval;
			} else
				$output [$k] = explode ( ",", $v );
		}
	
		return $output;
	}
	
	/**
	 * 返回数组的维度
	 * @param  [type] $arr [description]
	 * @return [type]      [description]
	 */
	public function arrayLevel($arr){
		$al = array(0);
		
		$al = $this->aL($arr,$al);
		return max($al);
	}
	private function aL($arr,&$al,$level=0){
		if(is_array($arr)){
			$level++;
			$al[] = $level;
			foreach($arr as $v){
				$this->aL($v,$al,$level);
			}
		}
		return $al;
	}
}