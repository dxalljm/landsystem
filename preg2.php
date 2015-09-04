<?php
$s1 = '1-100(123)';
$s2 = '1-200(3.9)';

// 匹配所有-开头的数字，
preg_match_all('/-([0-9]+)\(([0-9.]+?)\)/', $s2, $returns);
var_dump($returns);

/*
// s1
array(3) {
  [0] =>
  array(1) {
    [0] =>
    string(9) "-100(123)"
  }
  [1] =>
  array(1) {
    [0] =>
    string(3) "100"
  }
  [2] =>
  array(1) {
    [0] =>
    string(3) "123"
  }
}

// s2
array(3) {
  [0] =>
  array(1) {
    [0] =>
    string(9) "-200(456)"
  }
  [1] =>
  array(1) {
    [0] =>
    string(3) "200"
  }
  [2] =>
  array(1) {
    [0] =>
    string(3) "456"
  }
}

 */
