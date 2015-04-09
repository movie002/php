<?php

/*************** 开篇告诫，初学者**********************************
**************** 对数据结构有兴趣朋友******************************
* 必须要有比较好的C语言基础主要是指针这块知识，必须要理解指针******
* 我写的PHP都是从C语言的数据结构中演化而来************************
* 有兴趣的同学可以去可以从新学习一下C语言 与数据结构***************
* 说实在的php编程乃至java等的一些语言来说**************************
* 你不会数据结构也没什么*******************************************
* 但是你永远成不了真正的编程高手，以后工作基本上都是低端编程，*****
* 所以告诫初学者，要好好学习一下C语言中指针与数据结构**************
*******************************************************************
**********对下面程序有什么好的建议朋友或者是不懂的同学*************
********************可以Email联RenMengYangIT@163.com***********
*******************************************************************/

/**
 *  1.用PHP模拟一个静态队列 (以数组为列子 只有6个元素) 是一个循环队列
 *  2.pBase->初始化后指向一个数组
 *  3.front ->初始化为0，指向队列的第一个元素
 *  4.rear->初始化为0，指向最后一个元素的下一个元素
 *  5.end_queue-> 保存出队的值以后的数字 
 *  6.en_queue ->入队列的方法
 *  7.full_queue->判断队列是否已满
 *  8.empty_queue->判断队列是否为空
 *  9.traverse_queue->遍历队列
 *  10.out_queue->出队的函数
 *  @Author 任孟洋 
 *  @time   2013-8-10
 ***/

 Class  Queue 
 {

      public  $pBase;       //一个数组
      public  $front;       //指针指向队列的第一个元素 
      public  $rear;        //指针指向队列的最后一个元素
      public  $End_queue;   //记录出队的元素
      public  $Start_queue; //记录入队的元素
	  public  $Num;
       //初始化
      public  function  __construct($num){       
           $this->pBase =Array();
           $this->front =  0;
           $this->rear = 0;
		   $this->Num = $num;
      }

       // 入队
      public function en_queue(&$QUEUE,$val){

        if ( $this->full_queue($QUEUE))
		{
             return  FALSE; //失败        
        }
		else 
		{
           $QUEUE->pBase[$QUEUE->rear] = $val;      //把元素入队 （成功）         
           $QUEUE->rear =($QUEUE->rear+1) % $this->Num;//rear往上移动一位
           return   TRUE;
        }
      }

    //判断队列是否已满
   public function full_queue($QUEUE)
   {
		if(($QUEUE->rear+1) % $this->Num === $QUEUE->front)
		{            
            return  TRUE; //满  
        } 
		else 
		{ 
            return  FALSE; //没满    
        }
   }
  
	//判断是否为空
	public  function  empty_queue($QUEUE)
	{
		if ($QUEUE->front == $QUEUE->rear)
		{   
			return TRUE;

		}
		else
		{
			return FALSE;
		}
	}  
     
        //循环数输出队列
	public function  traverse_queue($QUEUE)
	{        
		$q = $QUEUE->front ;
		echo  '数列为<br/>';
		while ( $q != $QUEUE->rear)
		{
			print_r($QUEUE->pBase[$q]);
			echo  '<br/>';
			$q = ($q+1) % $this->Num;//向上移动一位
		}
	}
	
      //出队
	public function  out_queue($QUEUE)
	{
		if(!$this->empty_queue($QUEUE))
		{
			$this->End_queue = $QUEUE->pBase[$QUEUE->front];
			$QUEUE->front = ($QUEUE->front + 1) % $this->Num;  //向上移动一位       
		}
	}
}

//  //输出界面
//
//  //显示格式
//  header("Content-Type:text/html;charset=UTF-8;");
//  echo '<hr/>';
//  echo '没有初始化之前的空间是多少'.var_dump(memory_get_usage());
//  echo  '<hr/>'; 
//   //实例化
//  $QUEUE = new Queue(6);
//
//
//   /*入队*/
//  $QUEUE->en_queue($QUEUE,1);
//  $QUEUE->en_queue($QUEUE,2);
//  $QUEUE->en_queue($QUEUE,3);
//  $QUEUE->en_queue($QUEUE,4);
//  $QUEUE->en_queue($QUEUE,5);
//  $QUEUE->en_queue($QUEUE,6);
//  
//  /**出队**/
//  $QUEUE->out_queue($QUEUE);
//
//
//  /*入队*/
// $QUEUE->en_queue($QUEUE,6);
// $QUEUE->traverse_queue($QUEUE); //遍历
//
//  echo '<hr/>';
//  echo '出对以后的数字'.$QUEUE->End_queue.'<br/>';
//
//  echo '初始化以后'.var_dump(memory_get_usage());
//  echo  '<hr/>';
//  
?>