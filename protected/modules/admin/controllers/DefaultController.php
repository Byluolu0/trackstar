<?php

class DefaultController extends Controller
{
    
        public function init() 
        {
                $this->layout = 'column1';
        }
	public function actionIndex()
	{
		$this->render('index');
	}
}