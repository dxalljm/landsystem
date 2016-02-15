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
	private $echartsName;
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

	public function allcount()
	{
		return count($this->data);
	}
	
	public function sum($field,$num = 1) 
	{
		$Sum = 0.0;
		if($this->temp)
			$data = $this->temp;
		else
			$data = $this->data;
		foreach ($data as $key => $value) {
			if(is_numeric($value->getAttribute($field))) {
				$Sum += (float)sprintf("%.2f",$value->getAttribute($field)/$num);
			} else {
				return false;
			}
		}
		return $Sum;
	}
	public function mulOtherSum($field,$where,$otherfield,$num = 1)
	{
		$sum = 0.0;
		if($this->temp)
			$data = $this->temp;
		else
			$data = $this->data;
		foreach ($data as $key => $value) {
			$model = 'app\\models\\'.$otherfield[0];
			$fieldValue = $model::find()->where(['id'=>$value->getAttribute($where)])->one()[$otherfield[1]];
			$sum += bcmul($value->getAttribute($field),$fieldValue);
		}
		return sprintf("%.2f",$sum/$num);
	}
	public function otherSum($where,$otherfield,$num = 1)
	{
		$sum = 0.0;
		if($this->temp)
			$data = $this->temp;
		else
			$data = $this->data;
		foreach ($data as $key => $value) {
			$model = 'app\\models\\'.$otherfield[0];
			$sum += $model::find()->where(['id'=>$value->getAttribute($where)])->one()[$otherfield[1]];
		}
		return sprintf("%.2f",$sum/$num);
	}
	public function mulSum($field,$num = 1)
	{
		$Sum = 0.0;
		if($this->temp)
			$data = $this->temp;
		else
			$data = $this->data;
		foreach ($data as $key => $value) {
			if(is_numeric($value->getAttribute($field[0])) and is_numeric($value->getAttribute($field[1]))) {
				$mul = bcmul($value->getAttribute($field[0]), $value->getAttribute($field[1]));
				$Sum += (float)sprintf("%.2f",$mul/$num);
			} else {
				return false;
			}
		}
		return $Sum;
	}
	//$state: 0-$sum为总和，1-$sum为数组
	public function sumold($field,$where,$otherdb,$num = 1,$state = 0)
	{
// 		var_dump($this->echartsWhere);exit;
		if($state)
			$sum = [];
		else
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
// 					var_dump($this->echartsWhere);
					
					if($this->echartsWhere) {
// 						echo '111';
						$keys = array_keys($this->echartsWhere);
						$values = array_values($this->echartsWhere);
						if(isset($this->echartsWhere['management_area']) and is_array($this->echartsWhere['management_area'])) {
							foreach ($this->echartsWhere['management_area'] as $areaid) {
								foreach ($data as $value) {
									if(count($values) == 2) {
										if($value->getAttribute($keys[0]) == $areaid and $value->getAttribute($keys[1]) == $values[1]) {
		// 									echo '111';
											if(is_array($field[1])) {
												$model = 'app\\models\\'.$field[1][0];
												$fieldValue = $model::find()->where(['id'=>$value->getAttribute($field[1][1][0])])->one()[$field[1][1][1]];
											}
											if($state)
												$sum[] = (float)sprintf("%.2f",bcmul($value->getAttribute($field[0]),$fieldValue)/$num);
											else
												$sum += bcmul($value->getAttribute($field[0]),$fieldValue);
										}
									} else {
		// 								var_dump($this->echartsWhere);exit;
		// 								if($values[0])
										if($value->getAttribute($keys[0]) == $areaid) {
		// 									var_dump($field[1]);
											if(is_array($field[1])) {
												$model = 'app\\models\\'.$field[1][0];
												$fieldValue = $model::find()->where($field[1][1])->one()[$field[1][2]];
		
											if($state)
												$sum[] = (float)sprintf("%.2f",bcmul($value->getAttribute($field[0]),$fieldValue)/$num);
											else
												$sum += bcmul($value->getAttribute($field[0]),$fieldValue);
											}
		// 									var_dump($sum);
											
										}
									}
								}
							}
						}
					} else {
// 						echo '44444';
						foreach ($data as $value) {
							if(isset($field[0]) and is_array($field[0])) {
// 								echo '555';
								$sum += bcmul($value->getAttribute($field[0][0]),$value->getAttribute($field[0][1]));
							}
							if(isset($field[1]) and is_array($field[1])) {
// 								echo '666';
								$model = 'app\\models\\'.$field[1][0];
								$fieldValue = $model::find()->where(['id'=>$value->getAttribute($field[1][1][0])])->one()[$field[1][1][1]];
// 								var_dump($value->getAttribute($field[0]));
								if($state)
									$sum[] = (float)sprintf("%.2f",bcmul($value->getAttribute($field[0]),$fieldValue)/$num);
								else
									$sum += bcmul($value->getAttribute($field[0]),$fieldValue);
							}
// 							var_dump($sum);
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
						if($state)
							$sum[] = (float)sprintf("%.2f",bcmul($value->getAttribute($field[0]),$fieldValue)/$num);
						else
							$sum += bcmul($value->getAttribute($field[0]),$fieldValue);
					}
					break;
			}
// 			return sprintf("%.2f", $sum/$num);
		} else {
			$strArr = '';
// 			var_dump($this->echartsWhere);exit;
			if($this->echartsWhere) {
				$keys = array_keys($this->echartsWhere);
				$values = array_values($this->echartsWhere);
// 				var_dump($keys);exit;
				if(isset($this->echartsWhere['management_area']) and is_array($this->echartsWhere['management_area'])) {
					foreach ($this->echartsWhere['management_area'] as $areaid) {
						if(count($keys) == 2) {
							$Sum = 0.0;
							foreach ($data as $value) {
								if($value->getAttribute($keys[0]) == $areaid and $value->getAttribute($keys[1]) == $values[1]) {
									$Sum += (float)sprintf("%.2f",$value->getAttribute($field)/$num);
								}								
							}
							if($state)
								$sum[] = $Sum;
							else
								$sum = $Sum;
						} else {
// 							echo 'jjjjjj';
							$Sum = 0.0;
							foreach ($data as $value) {
								if($value->getAttribute($keys[0]) == $areaid) {
									$Sum += (float)sprintf("%.2f",$value->getAttribute($field)/$num);
								}								
							}
							if($state)
								$sum[] = $Sum;
							else
								$sum = $Sum;
						}
					}
				} else {
// 					var_dump($values);exit;
					$Sum = 0.0;
					foreach ($data as $value) {
						if($value->getAttribute($keys[0]) == $values[0] and $value->getAttribute($keys[1]) == $values[1]) {
							$Sum += (float)sprintf("%.2f",$value->getAttribute($field)/$num);
						}						
					}
					if($state)
						$sum[] = $Sum;
					else
						$sum = $Sum;
				}
			} else {
				var_dump($field);
				$Sum = 0.0;
				foreach ($data as $key => $value) {
					if(is_numeric($value->getAttribute($field))) {
						$Sum += (float)sprintf("%.2f",$value->getAttribute($field)/$num);
					}
				}
				if($state)
					$sum[] = $Sum;
				else
					$sum = $Sum;
			}
		}
		if($sum)
			return $sum;
		else
			return 0.0;
		
			
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
				if($this->arrayLevel($olddata) == 2) {
					$newdata = $this->unique_arr($olddata);
				} else 
					return 0;
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
		if($model !== 'self')
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
			if($model !== 'self') {
				$nameArray = $modelclass::find()->where(['id'=>$newdata])->all();
				foreach ($nameArray as $value) {
					$result[$value['id']] = $value[$getfield];
				}
			} else {
				foreach ($newdata as $value) {
					$result[$value] = $value;
				}
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
	public function setEchartsName($array) 
	{
		$this->echartsName = $array;
		return $this;
	}
	public function getList()
	{
		return $this->namelist;
	}
	
	public function getEchartsData($field,$num = 1,$function = 'showAllShadow',$state = 0)
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
			if($function == 'showAllShadow') {
				$sum = [];
				if($this->namelist) {
					foreach ($this->namelist as $key => $list) {
// 						var_dump($this->field);
						$sum[] = $this->where(['management_area'=>$areaid,$this->field=>$key])->sum($field,$num,$state);
// 						var_dump($sum);
					}
				} else {
					$sum[] = $this->where(['management_area'=>$areaid])->sum($field,$num,$state);
				}
				$result[] = [
						'name' => str_ireplace('管理区', '', ManagementArea::find()->where(['id'=>$areaid])->one()['areaname']),
						'type' => 'bar',
						'stack' => $areaid,
						'data' => $sum
				];
			}
		}
// 		var_dump($management_area);
			if($function == 'showShadowThermometer') {
				$sum = [];
				if($this->namelist) {
					foreach ($this->namelist as $key => $list) {
// 						var_dump($list);
						$sum0 = (float)$this->where(['management_area'=>$management_area,$this->field=>$key])->sum($field[0],$num,$state);
						$sum1 = (float)$this->where(['management_area'=>$management_area,$this->field=>$key])->sum($field[1],$num,$state);
						$sum[$field[0]][] = $sum0;
						$sum[$field[1]][] = $sum1 - $sum0;
						
					}
				} else {
					echo 'iiiiii';
					$sum0 = $this->where(['management_area'=>$management_area])->sum($field[0],$num,$state);
					$sum1 = $this->where(['management_area'=>$management_area])->sum($field[1],$num,$state);
					$sum[$field[0]][] = $sum0;
// 					var_dump($sum1);
					if(is_array($field[1]))
						$key1 = $field[1][1][1].$field[1][0].$field[1][1][2];
					else
						$key1 = $field[1];
					if($state) {
						for($i=0;$i<count($sum0);$i++) {
// 							var_dump($sum1);
							$sum[$key1][] = $sum1[$i] - $sum0[$i];
						}
					} else 
						$sum[$key1][] = $sum1 - $sum0;
				}
				var_dump($sum);
				$result[] = [[
					'name' => $this->echartsName[0],
					'type' => 'bar',
					'stack' => 'sum',
					'barCategoryGap'=>'50%',
					'itemStyle'=>[
						'normal'=> [
							'color'=> 'tomato',
							'barBorderColor'=> 'tomato',
							'barBorderWidth'=> 3,
							'barBorderRadius'=>0,
							'label'=>[
								'show'=> true, 
								'position'=> 'insideTop'
							]
						]
					],
					'data'=>$sum[$field[0]],
				],
				[
					'name' => $this->echartsName[1],
					'type' => 'bar',
					'stack' => 'sum',
					'itemStyle'=> [
						'normal'=> [
							'color'=>'#fff',
							'barBorderColor'=> 'tomato',
							'barBorderWidth'=> 3,
							'barBorderRadius'=>0,
							'label' => [
								'show'=> true,
								'position'=> 'top',
		// 						'formatter'=> '{c}',
								'textStyle'=>[
									'color'=> 'tomato'
								]
							]
						]
					],
					'data'=>$sum[$key1],
				]];
			}
// 		}
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