<!--
{{ $page_title = 'Importing Orders Into Magento from a CSV Using PHP' }}
{{ $page_body_class = 'page-blog-post' }}
-->

@extends('_layouts.master')

@section('body')

@include('_partials.jumbotron', ['main_msg' => 'Importing Orders Into Magento from a CSV Using PHP', 'sub_txt' => 'Posted on September 5, 2010 at 7:20 pm'])



<div class="container">

<div class="row">

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 post-content">


<p>Magento is a wonderful open source eCommerce platform written in PHP that provides are rich inventory management system that lacks a few features that if implemented and save time and money. One of those features missing is the ability to import Orders from CSV. In this post I will show off a crude method I&#8217;ve used in the past to accomplish this feat.<br />
<span id="more-81"></span></p>
<p>For many people using Magento as an eccomerce platform they would like to export orders on a daily basis as part of the fulfillment process. Luckly there is a great module for that purpose available here:<br />
<a href="http://www.magentocommerce.com/magento-connect/slandsbek/extension/1350/simple-order-export">http://www.magentocommerce.com/magento-connect/slandsbek/extension/1350/simple-order-export</a></p>
<p>Using the Simple Export module you can export your orders to a CSV, but Magento doesn&#8217;t do a good job of allowing you to import orders that have been shipped back into the system.</p>
<p>The script below will take a CSV with &#8220;OrderNumber,Email,TrackingNumber	,Carrier&#8221; and import the orders back into Magento and mark them as Shipped/Complete and include Tracking Numbers that are sent to the customer.</p>
<p>I&#8217;ve segregated the PHP function into 3 files. You can <a href="http://www.tegdesign.com/Order_Import.zip">download a zip file here</a>.</p>
<p><strong>File: form.php</strong><br />
This file allows you to browse for the CSV you wish to import. Make sure your directory has write permissions to upload the CSV files.</p>


<pre><code>
&lt;form enctype=&quot;multipart/form-data&quot; action=&quot;&quot; method=&quot;POST&quot;&gt;
  &lt;p&gt;
    &lt;input type=&quot;hidden&quot; name=&quot;MAX_FILE_SIZE&quot; value=&quot;1000000000&quot; /&gt;
	CSV File:
    &lt;input name=&quot;uploadedfile&quot; type=&quot;file&quot; /&gt;
  &lt;/p&gt;
  &lt;p&gt;
    &lt;input type=&quot;submit&quot; name=&quot;upload&quot; id=&quot;upload&quot; value=&quot;Submit&quot; /&gt;
  &lt;/p&gt;
&lt;/form&gt;
</code></pre>


<p><strong>File: Import.php</strong><br />
Make sure that wherever you upload the files to you reference the correct location of Mage.php</p>


<pre><code class="php">
&lt;?php
	require_once(&quot;app/Mage.php&quot;);
	Mage::app();
	
	include('updateOrder.php');
  
	if (isset($_POST['MAX_FILE_SIZE'])) {

		$email = true;
  
		$target_path = basename( $_FILES['uploadedfile']['name']); 
		$_FILES['uploadedfile']['tmp_name'];  
		$target_path = basename( $_FILES['uploadedfile']['name']); 
		  
		if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
?&gt;

&lt;div style=&quot;font-size: 13px; font-family: Arial, Helvetica, sans-serif; color: #060; font-weight: bold; background-color:#FFC; padding:10px;&quot;&gt;
Order Import Succesfully!
&lt;br /&gt;
&lt;span style=&quot;font-size: 10px; font-style:italic; font-family: Arial, Helvetica, sans-serif; color: #333; padding:15px;&quot;&gt;
The file &lt;?php echo basename( $_FILES['uploadedfile']['name']); ?&gt; has been uploaded.
&lt;/span&gt;
&lt;/div&gt;


&lt;?php	
		  	ini_set(&quot;auto_detect_line_endings&quot;, 1); 
		  	$current_row = 1; 
		  	$handle = fopen($target_path, &quot;r&quot;); 
		  	$csvData = array();
		  
			while ( ($data = fgetcsv($handle, 10000, &quot;,&quot;) ) !== FALSE ) 
			{ 
				$number_of_fields = count($data); 
				if ($current_row == 1) { 	//Header line 
					for ($c=0; $c &lt; $number_of_fields; $c++) 
					{ 
						$header_array[$c] = $data[$c];
					} 
				} else { 	//Data line 
					for ($c=0; $c &lt; $number_of_fields; $c++) 
					{ 
						$data_array[$header_array[$c]] = $data[$c];
					}
					$csvData[] = $data_array;
				} 
				$current_row++; 
			} 
			
			fclose($handle); 
		  
		  	foreach($csvData as $rec) {
			  updateOrder($rec['OrderNumber'], 
								  $rec['Email'],
								  $rec['Carrier'],
								  $rec['TrackingNumber']);
			}
		  
		} //end if statment for file upload check
		
	} else { // end if statement for post check for upload

		include('form.php');
	
	}

?&gt;
</code></pre>


<p>File: updateOrder.php</p>


<pre><code class="php">
&lt;?php
  function updateOrder($orderId, $email, $carrier, $trackingNum) {
	$includeComment = false;
	$comment = NULL;
	
	$order = Mage::getModel('sales/order')-&gt;loadByIncrementId($orderId);
	
	//This converts the order to &quot;Completed&quot;.
	$convertor = Mage::getModel('sales/convert_order');
	$shipment = $convertor-&gt;toShipment($order);
	
	//other methods to investigate
	// $convertor-&gt;toInvoice
	// $convertor-&gt;toCreditmemo($order)
	
	// for information on how these methods are ran I usually run a find in SSH:
	// find -name '*.php' -print0 | xargs -0 grep '$convertor-&gt;to'

	
	foreach ($order-&gt;getAllItems() as $orderItem) {
		
		if (!$orderItem-&gt;getQtyToShip()) {
			continue;
		}
		if ($orderItem-&gt;getIsVirtual()) {
			continue;
		}
		
		$item = $convertor-&gt;itemToShipmentItem($orderItem);
	
		$qty = $orderItem-&gt;getQtyToShip();
	
		$item-&gt;setQty($qty);
		$shipment-&gt;addItem($item);
	}
	
	$carrierTitle = NULL;
	
	// Reference the Magento admin
	// Look for the shipping information by selecting an order that is completed
	// Click the Shipments tab
	// Click on the Shipment
	// Scroll down to Shipping and Tracking Information box
	// The $carrier variable must match what magento uses for its shortname.
	// An easy way to find out what magento uses is to view HTML source code of the Shipping page in your browser
	// Do a Search for &quot;Custom Value&quot;
	// You will find the form like this:
	
	//	 &lt;select name=&quot;carrier&quot; class=&quot;select&quot; style=&quot;width:110px&quot; onchange=&quot;selectCarrier(this)&quot;&gt;
	//                                        &lt;option value=&quot;custom&quot;&gt;Custom Value&lt;/option&gt;
	//                                        &lt;option value=&quot;dhl&quot;&gt;DHL&lt;/option&gt;
	//                                        &lt;option value=&quot;fedex&quot;&gt;Federal Express&lt;/option&gt;
	//                                        &lt;option value=&quot;ups&quot;&gt;United Parcel Service&lt;/option&gt;
	//                                        &lt;option value=&quot;usps&quot;&gt;United States Postal Service&lt;/option&gt;
	//                                    &lt;/select&gt;
	
	// $carrier = whatever the &lt;option value=XXX is
	// $carrierTitle = whatever the text is for that option
	
	if ($carrier == 'ups') {
		$carrierTitle = 'United Parcel Service';
	}
	
	if ($carrier == 'usps') {
		$carrierTitle = 'United States Postal Service';
	}
	
	if ($carrier == 'some_other_carrier') {
		$carrierTitle = 'Some other carrier';
	}
	
	$data = array();
	$data['carrier_code'] = $carrier;
	$data['title'] = $carrierTitle; 
	$data['number'] = $trackingNum;
	
	$track = Mage::getModel('sales/order_shipment_track')-&gt;addData($data);
	$shipment-&gt;addTrack($track);
	
	// Other methods to investigate and reverse engineer
	// Mage::register('current_shipment', $shipment);
	// Mage::register('current_order', $order);
	// Mage::register('current_invoice', $invoice);

	
	$shipment-&gt;register();
	$shipment-&gt;addComment($comment, $email &amp;&amp; $includeComment);
	$shipment-&gt;setEmailSent(true);
	$shipment-&gt;getOrder()-&gt;setIsInProcess(true);
	
	
	$transactionSave = Mage::getModel('core/resource_transaction')
		-&gt;addObject($shipment)
		-&gt;addObject($shipment-&gt;getOrder())
		-&gt;save();
	
	$shipment-&gt;sendEmail($email, ($includeComment ? $comment : ''));
	
	$shipment-&gt;save();
	
	return;
	
  }
?&gt;
</code></pre>


	    </div>
	    <footer>
	      	      <ul class="entry-tags"><li><a href="/tag/magento/" rel="tag">Magento</a></li><li><a href="/tag/php/" rel="tag">PHP</a></li></ul>	    </footer>
	    
<div id="disqus_thread">
            <div id="dsq-content">


            <ul id="dsq-comments">
                    <li class="comment even thread-even depth-1" id="dsq-comment-2">
        <div id="dsq-comment-header-2" class="dsq-comment-header">
            <cite id="dsq-cite-2">
                <span id="dsq-author-user-2">g</span>
            </cite>
        </div>
        <div id="dsq-comment-body-2" class="dsq-comment-body">
            <div id="dsq-comment-message-2" class="dsq-comment-message"><p>thanks for your work!<br />
the .zip can not be accessed, though.<br />
regards<br />
-g</p>
</div>
        </div>

    <ul class="children">
    <li class="comment byuser comment-author-tegan bypostauthor odd alt depth-2" id="dsq-comment-3">
        <div id="dsq-comment-header-3" class="dsq-comment-header">
            <cite id="dsq-cite-3">
                <span id="dsq-author-user-3">tegan</span>
            </cite>
        </div>
        <div id="dsq-comment-body-3" class="dsq-comment-body">
            <div id="dsq-comment-message-3" class="dsq-comment-message"><p>The Zip file is now accessible. It also contains an example to read the CSV file from a remote FTP server.</p>
</div>
        </div>

    </li><!-- #comment-## -->
</ul><!-- .children -->
</li><!-- #comment-## -->
    <li class="comment even thread-odd thread-alt depth-1" id="dsq-comment-4">
        <div id="dsq-comment-header-4" class="dsq-comment-header">
            <cite id="dsq-cite-4">
                <span id="dsq-author-user-4">g</span>
            </cite>
        </div>
        <div id="dsq-comment-body-4" class="dsq-comment-body">
            <div id="dsq-comment-message-4" class="dsq-comment-message"><p>So I tried to get this to work with the code snippets. No success sofar.<br />
Here is what I did:</p>
<p>1. created 3 files &#8220;updateOrder.php&#8221;, &#8220;Import.php&#8221; and &#8220;form.php&#8221; with the corresponding code from above in the magento root directory.</p>
<p>2. changed the $carrier to &#8220;dhl&#8221; and the $carrierTitle to &#8220;DHL&#8221; as shown in my Magento shippingpage source code.</p>
<p>3. Constructed a CSV with the column headers &#8220;OrderNumber&#8221;, &#8220;Email&#8221;, &#8220;TrackingNumber&#8221;, &#8220;Carrier&#8221;</p>
<p>Used ordernumbers and emails that are in the System. Used random Trackingnumbers and &#8220;DHL&#8221; as carrier.</p>
<p>4. Went to <a href="http://www.mysite.com/magentodir/form.php" rel="nofollow">http://www.mysite.com/magentodir/form.php</a></p>
<p>selected the csv-file and hit upload. Nothing happened.</p>
<p>Hope you can help me.</p>
<p>Thanks<br />
-g</p>
</div>
        </div>

    <ul class="children">
    <li class="comment byuser comment-author-tegan bypostauthor odd alt depth-2" id="dsq-comment-5">
        <div id="dsq-comment-header-5" class="dsq-comment-header">
            <cite id="dsq-cite-5">
                <span id="dsq-author-user-5">tegan</span>
            </cite>
        </div>
        <div id="dsq-comment-body-5" class="dsq-comment-body">
            <div id="dsq-comment-message-5" class="dsq-comment-message"><p>Have you tried to use the Zip File yet? I just fixed the link. I&#8217;m heading off to run a few errands and will be back in a few hours to help you more if u still need it.</p>
</div>
        </div>

    <ul class="children">
    <li class="comment even depth-3" id="dsq-comment-6">
        <div id="dsq-comment-header-6" class="dsq-comment-header">
            <cite id="dsq-cite-6">
                <span id="dsq-author-user-6">g</span>
            </cite>
        </div>
        <div id="dsq-comment-body-6" class="dsq-comment-body">
            <div id="dsq-comment-message-6" class="dsq-comment-message"><p>Just tried it with the .zip.</p>
<p>No success either.</p>
<p>I am not clear how this whole thing is started. Just by hitting the submit button in form.php?</p>
<p>Hope you can explain this to me later.</p>
<p>Thanks anyway</p>
</div>
        </div>

    <ul class="children">
    <li class="comment byuser comment-author-tegan bypostauthor odd alt depth-4" id="dsq-comment-8">
        <div id="dsq-comment-header-8" class="dsq-comment-header">
            <cite id="dsq-cite-8">
                <span id="dsq-author-user-8">tegan</span>
            </cite>
        </div>
        <div id="dsq-comment-body-8" class="dsq-comment-body">
            <div id="dsq-comment-message-8" class="dsq-comment-message"><p>Yes, the submit button should submit the form to itself. Make sure you have write permissions on the directory (CHMOD 775 or 777). Make sure you have the path set to Mage.php correctly at the start of the file.</p>
<p>I haven&#8217;t tested this but this should work also&#8230; it is another stripped down version without the function.</p>


<pre><code class="php">
&lt;?php
	require_once(&quot;app/Mage.php&quot;);
	Mage::app();
  
	if (isset($_POST['MAX_FILE_SIZE'])) {

		$email = true;
  
		$target_path = basename( $_FILES['uploadedfile']['name']); 
		$_FILES['uploadedfile']['tmp_name'];  
		$target_path = basename( $_FILES['uploadedfile']['name']); 
		  
		if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
?&gt;

The file &lt;?php echo basename( $_FILES['uploadedfile']['name']); ?&gt; has been uploaded succesfully!


&lt;?php	
		  	ini_set(&quot;auto_detect_line_endings&quot;, 1); 
		  	$current_row = 1; 
		  	$handle = fopen($target_path, &quot;r&quot;); 
		  	$csvData = array();
		  
			while ( ($data = fgetcsv($handle, 10000, &quot;,&quot;) ) !== FALSE ) 
			{ 
				$number_of_fields = count($data); 
				if ($current_row == 1) { 	//Header line 
					for ($c=0; $c &lt; $number_of_fields; $c++) 
					{ 
						$header_array[$c] = $data[$c];
					} 
				} else { 	//Data line 
					for ($c=0; $c &lt; $number_of_fields; $c++) 
					{ 
						$data_array[$header_array[$c]] = $data[$c];
					}
					$csvData[] = $data_array;
				} 
				$current_row++; 
			} 
			
			fclose($handle); 
		  
		  	foreach($csvData as $rec) {
											  
				$includeComment = false;
				$comment = NULL;
				
				$order = Mage::getModel('sales/order')-&gt;loadByIncrementId($rec['TrackingNumber']);
				
				//This converts the order to &quot;Completed&quot;.
				$convertor = Mage::getModel('sales/convert_order');
				$shipment = $convertor-&gt;toShipment($order);
			
				foreach ($order-&gt;getAllItems() as $orderItem) {
					
					if (!$orderItem-&gt;getQtyToShip()) {
						continue;
					}
					if ($orderItem-&gt;getIsVirtual()) {
						continue;
					}
					
					$item = $convertor-&gt;itemToShipmentItem($orderItem);
				
					$qty = $orderItem-&gt;getQtyToShip();
				
					$item-&gt;setQty($qty);
					$shipment-&gt;addItem($item);
				}
				
				$carrierTitle = NULL;
				
				// FOR GUIDANCE ON THIS SECTION LOOK AT MY FIRST POST... IT HAS BETTER COMMENTS
				
				if ($rec['Carrier'] == 'ups') {
					$carrierTitle = 'United Parcel Service';
				}
				
				if ($rec['Carrier'] == 'usps') {
					$carrierTitle = 'United States Postal Service';
				}
				
				if ($rec['Carrier'] == 'some_other_carrier') {
					$carrierTitle = 'Some other carrier';
				}
				
				$data = array();
				$data['carrier_code'] = $rec['Carrier'];
				$data['title'] = $carrierTitle; 
				$data['number'] = $rec['TrackingNumber'];
				
				$track = Mage::getModel('sales/order_shipment_track')-&gt;addData($data);
				$shipment-&gt;addTrack($track);
				
				$shipment-&gt;register();
				$shipment-&gt;addComment($comment, $rec['Email'] &amp;&amp; $includeComment);
				$shipment-&gt;setEmailSent(true);
				$shipment-&gt;getOrder()-&gt;setIsInProcess(true);
				
				
				$transactionSave = Mage::getModel('core/resource_transaction')
					-&gt;addObject($shipment)
					-&gt;addObject($shipment-&gt;getOrder())
					-&gt;save();
				
				$shipment-&gt;sendEmail($rec['Email'], ($includeComment ? $comment : ''));
				
				$shipment-&gt;save();		  
								  
			}
		  
		} //end if statment for file upload check
		
	} else { // end if statement for post check for upload

?&gt;

&lt;form enctype=&quot;multipart/form-data&quot; action=&quot;&quot; method=&quot;POST&quot;&gt;
  &lt;p&gt;
    &lt;input type=&quot;hidden&quot; name=&quot;MAX_FILE_SIZE&quot; value=&quot;1000000000&quot; /&gt;
	CSV File:
    &lt;input name=&quot;uploadedfile&quot; type=&quot;file&quot; /&gt;
  &lt;/p&gt;
  &lt;p&gt;
    &lt;input type=&quot;submit&quot; name=&quot;upload&quot; id=&quot;upload&quot; value=&quot;Submit&quot; /&gt;
  &lt;/p&gt;
&lt;/form&gt;


&lt;?php
	
	}

?&gt;

</code></pre>


</div>
        </div>

    </li><!-- #comment-## -->
</ul><!-- .children -->
</li><!-- #comment-## -->
</ul><!-- .children -->
</li><!-- #comment-## -->
</ul><!-- .children -->
</li><!-- #comment-## -->
    <li class="comment byuser comment-author-tegan bypostauthor even thread-even depth-1" id="dsq-comment-7">
        <div id="dsq-comment-header-7" class="dsq-comment-header">
            <cite id="dsq-cite-7">
                <span id="dsq-author-user-7">tegan</span>
            </cite>
        </div>
        <div id="dsq-comment-body-7" class="dsq-comment-body">
            <div id="dsq-comment-message-7" class="dsq-comment-message"><p>Here is a stripped down version with just one file handling it all.</p>


<pre><code class="php">
&lt;?php
	require_once(&quot;app/Mage.php&quot;);
	Mage::app();
	
  function updateOrder($orderId, $email, $carrier, $trackingNum) {
	$includeComment = false;
	$comment = NULL;
	
	$order = Mage::getModel('sales/order')-&gt;loadByIncrementId($orderId);
	
	//This converts the order to &quot;Completed&quot;.
	$convertor = Mage::getModel('sales/convert_order');
	$shipment = $convertor-&gt;toShipment($order);

	foreach ($order-&gt;getAllItems() as $orderItem) {
		
		if (!$orderItem-&gt;getQtyToShip()) {
			continue;
		}
		if ($orderItem-&gt;getIsVirtual()) {
			continue;
		}
		
		$item = $convertor-&gt;itemToShipmentItem($orderItem);
	
		$qty = $orderItem-&gt;getQtyToShip();
	
		$item-&gt;setQty($qty);
		$shipment-&gt;addItem($item);
	}
	
	$carrierTitle = NULL;
	
	// READ MY COMMENTS REGARDING THIS SECTION ABOVE.
	
	if ($carrier == 'ups') {
		$carrierTitle = 'United Parcel Service';
	}
	
	if ($carrier == 'usps') {
		$carrierTitle = 'United States Postal Service';
	}
	
	$data = array();
	$data['carrier_code'] = $carrier;
	$data['title'] = $carrierTitle; 
	$data['number'] = $trackingNum;
	
	$track = Mage::getModel('sales/order_shipment_track')-&gt;addData($data);
	$shipment-&gt;addTrack($track);
	
	$shipment-&gt;register();
	$shipment-&gt;addComment($comment, $email &amp;&amp; $includeComment);
	$shipment-&gt;setEmailSent(true);
	$shipment-&gt;getOrder()-&gt;setIsInProcess(true);
	
	
	$transactionSave = Mage::getModel('core/resource_transaction')
		-&gt;addObject($shipment)
		-&gt;addObject($shipment-&gt;getOrder())
		-&gt;save();
	
	$shipment-&gt;sendEmail($email, ($includeComment ? $comment : ''));
	
	$shipment-&gt;save();
	
	return;
	
  }
  
	if (isset($_POST['MAX_FILE_SIZE'])) {

		$email = true;
  
		$target_path = basename( $_FILES['uploadedfile']['name']); 
		$_FILES['uploadedfile']['tmp_name'];  
		$target_path = basename( $_FILES['uploadedfile']['name']); 
		  
		if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
?&gt;



The file &lt;?php echo basename( $_FILES['uploadedfile']['name']); ?&gt; has been uploaded succesfully!


&lt;?php	
		  	ini_set(&quot;auto_detect_line_endings&quot;, 1); 
		  	$current_row = 1; 
		  	$handle = fopen($target_path, &quot;r&quot;); 
		  	$csvData = array();
		  
			while ( ($data = fgetcsv($handle, 10000, &quot;,&quot;) ) !== FALSE ) 
			{ 
				$number_of_fields = count($data); 
				if ($current_row == 1) { 	//Header line 
					for ($c=0; $c &lt; $number_of_fields; $c++) 
					{ 
						$header_array[$c] = $data[$c];
					} 
				} else { 	//Data line 
					for ($c=0; $c &lt; $number_of_fields; $c++) 
					{ 
						$data_array[$header_array[$c]] = $data[$c];
					}
					$csvData[] = $data_array;
				} 
				$current_row++; 
			} 
			
			fclose($handle); 
		  
		  	foreach($csvData as $rec) {
			  updateOrder($rec['OrderNumber'], 
								  $rec['Email'],
								  $rec['Carrier'],
								  $rec['TrackingNumber']);
			}
		  
		} //end if statment for file upload check
		
	} else { // end if statement for post check for upload

?&gt;

&lt;form enctype=&quot;multipart/form-data&quot; action=&quot;&quot; method=&quot;POST&quot;&gt;
  &lt;p&gt;
    &lt;input type=&quot;hidden&quot; name=&quot;MAX_FILE_SIZE&quot; value=&quot;1000000000&quot; /&gt;
	CSV File:
    &lt;input name=&quot;uploadedfile&quot; type=&quot;file&quot; /&gt;
  &lt;/p&gt;
  &lt;p&gt;
    &lt;input type=&quot;submit&quot; name=&quot;upload&quot; id=&quot;upload&quot; value=&quot;Submit&quot; /&gt;
  &lt;/p&gt;
&lt;/form&gt;


&lt;?php
	
	}

?&gt;
</code></pre>


</div>
        </div>

    <ul class="children">
    <li class="comment odd alt depth-2" id="dsq-comment-9">
        <div id="dsq-comment-header-9" class="dsq-comment-header">
            <cite id="dsq-cite-9">
                <span id="dsq-author-user-9">g</span>
            </cite>
        </div>
        <div id="dsq-comment-body-9" class="dsq-comment-body">
            <div id="dsq-comment-message-9" class="dsq-comment-message"><p>Ok, the upload works now.<br />
But I get this error:</p>
<p>Fatal error: Uncaught exception &#8216;Mage_Core_Exception&#8217; with message &#8216;Cannot create an empty shipment.&#8217; in /var/www/vhosts/mysite.com/httpdocs/magento_16/app/Mage.php:550 Stack trace: #0 /var/www/vhosts/mysite.com/httpdocs/magento_16/app/code/core/Mage/Sales/Model/Order/Shipment.php(504): Mage::throwException(&#8216;Cannot create a&#8230;&#8217;) #1 /var/www/vhosts/mysite.com/httpdocs/magento_16/app/code/core/Mage/Core/Model/Abstract.php(304): Mage_Sales_Model_Order_Shipment-&gt;_beforeSave() #2 /var/www/vhosts/mysite.com/httpdocs/magento_16/app/code/core/Mage/Core/Model/Resource/Transaction.php(150): Mage_Core_Model_Abstract-&gt;save() #3 /var/www/vhosts/mysite.com/httpdocs/magento_16/test.php(60): Mage_Core_Model_Resource_Transaction-&gt;save() #4 /var/www/vhosts/mysite.com/httpdocs/magento_16/test.php(113): updateOrder(&#8216;100000010&#8242;, &#8216;test@googlema&#8230;&#8217;, &#8216;dhl&#8217;, &#8216;555911&#8217;) #5 {main} thrown in /var/www/vhosts/mysite.com/httpdocs/magento_16/app/Mage.php on line 550</p>
</div>
        </div>

    <ul class="children">
    <li class="comment byuser comment-author-tegan bypostauthor even depth-3" id="dsq-comment-10">
        <div id="dsq-comment-header-10" class="dsq-comment-header">
            <cite id="dsq-cite-10">
                <span id="dsq-author-user-10">tegan</span>
            </cite>
        </div>
        <div id="dsq-comment-body-10" class="dsq-comment-body">
            <div id="dsq-comment-message-10" class="dsq-comment-message"><p>Does the order exist in the system?</p>
</div>
        </div>

    </li><!-- #comment-## -->
</ul><!-- .children -->
</li><!-- #comment-## -->
</ul><!-- .children -->
</li><!-- #comment-## -->
    <li class="comment odd alt thread-odd thread-alt depth-1" id="dsq-comment-11">
        <div id="dsq-comment-header-11" class="dsq-comment-header">
            <cite id="dsq-cite-11">
                <span id="dsq-author-user-11">g</span>
            </cite>
        </div>
        <div id="dsq-comment-body-11" class="dsq-comment-body">
            <div id="dsq-comment-message-11" class="dsq-comment-message"><p>You are right: My .csv was messed up!<br />
Now there are no errors.</p>
<p>Is there a way to trigger the invoices, too?</p>
<p>Your script would be a great together with two other hacks I looked into. My intended workflow would be as follows:</p>
<p>1. <a href="http://www.webguys.de/magento/events-in-magento-export-bei-neuer-bestellung/" rel="nofollow">http://www.webguys.de/magento/events-in-magento-export-bei-neuer-bestellung/</a></p>
<p>It&#8217;s in german. The small module generates an event whenever a order is checked out. This should trigger the following</p>
<p>2. order export to xml/csv</p>
<p>I downloaded and tried msn&#8217;s module @post #71 in this thread </p>
<p><a href="http://www.magentocommerce.com/boards/viewthread/28679/P60/#" rel="nofollow">http://www.magentocommerce.com/boards/viewthread/28679/P60/#</a></p>
<p>I also patched in the CSV-support you can find here:</p>
<p><a href="http://www.magentocommerce.com/boards/viewthread/28679/P105/" rel="nofollow">http://www.magentocommerce.com/boards/viewthread/28679/P105/</a> @ #116</p>
<p>3. Automatically download the generated CSV to local machine (i.e. with something like <a href="http://www.sitedesigner.com/batchsync_secure.htm" rel="nofollow">http://www.sitedesigner.com/batchsync_secure.htm</a> )</p>
<p>4. Import and process the CSV with MS Access.</p>
<p>5. Export a new CSV that triggers your Order Import Script.</p>
<p>In theory this is very doable.<br />
So far I see necessary modification at the steps above:</p>
<p>@ 1.  	Write an event that triggers the export module.<br />
@ 2.	Automatically generate a CSV (so far only XML&#8217;s are put into folder &#8211; CSV&#8217;s have to be manually generated and downloaded)<br />
@ 5.	Find a way to trigger the import whenever the import-csv is updated.</p>
<p>I think this would be a really good generic bridge to a wide range of external order management applications.</p>
<p>Let me know what you think.<br />
-g</p>
</div>
        </div>

    </li><!-- #comment-## -->
    <li class="comment even thread-even depth-1" id="dsq-comment-12">
        <div id="dsq-comment-header-12" class="dsq-comment-header">
            <cite id="dsq-cite-12">
                <span id="dsq-author-user-12">g</span>
            </cite>
        </div>
        <div id="dsq-comment-body-12" class="dsq-comment-body">
            <div id="dsq-comment-message-12" class="dsq-comment-message"><p>You are right: My .csv was messed up!<br />
Now there are no errors.</p>
</div>
        </div>

    <ul class="children">
    <li class="comment byuser comment-author-tegan bypostauthor odd alt depth-2" id="dsq-comment-15">
        <div id="dsq-comment-header-15" class="dsq-comment-header">
            <cite id="dsq-cite-15">
                <span id="dsq-author-user-15">tegan</span>
            </cite>
        </div>
        <div id="dsq-comment-body-15" class="dsq-comment-body">
            <div id="dsq-comment-message-15" class="dsq-comment-message"><p>Glad you got it working.</p>
</div>
        </div>

    </li><!-- #comment-## -->
</ul><!-- .children -->
</li><!-- #comment-## -->
    <li class="comment even thread-odd thread-alt depth-1" id="dsq-comment-13">
        <div id="dsq-comment-header-13" class="dsq-comment-header">
            <cite id="dsq-cite-13">
                <span id="dsq-author-user-13">g</span>
            </cite>
        </div>
        <div id="dsq-comment-body-13" class="dsq-comment-body">
            <div id="dsq-comment-message-13" class="dsq-comment-message"><p>I posted this at the magento forum, too.</p>
<p>Here is what I think this could do in combination with two other hacks I looked into. My intended workflow would be as follows:</p>
<p>1. <a href="http://www.webguys.de/magento/events-in-magento-export-bei-neuer-bestellung/" rel="nofollow">http://www.webguys.de/magento/events-in-magento-export-bei-neuer-bestellung/</a><br />
It’s in german. The small module generates an event whenever a order is checked out. This should trigger the following</p>
<p>2. order export to xml/csv<br />
I downloaded and tried msn’s module @post #71 in this thread<br />
<a href="http://www.magentocommerce.com/boards/viewthread/28679/P60/#" rel="nofollow">http://www.magentocommerce.com/boards/viewthread/28679/P60/#</a><br />
I also patched in the CSV-support you can find here:<br />
<a href="http://www.magentocommerce.com/boards/viewthread/28679/P105/" rel="nofollow">http://www.magentocommerce.com/boards/viewthread/28679/P105/</a> @ #116</p>
<p>3. Automatically download the generated CSV to local machine (i.e. with something like <a href="http://www.sitedesigner.com/batchsync_secure.htm" rel="nofollow">http://www.sitedesigner.com/batchsync_secure.htm</a> )</p>
<p>4. Import and process the CSV with MS Access.</p>
<p>5. Export a new CSV that triggers your Order Import Script.</p>
<p>In theory this is very doable.<br />
So far I see necessary modification at these steps:</p>
<p>@ 1.  Write an event that triggers the export module.<br />
@ 2.  Automatically generate a CSV (so far only XML’s are put into folder &#8211; CSV’s have to be manually generated and downloaded)<br />
@ 5.  Find a way to trigger the import whenever the import-csv is updated.</p>
<p>I think this would be a really good generic bridge to a wide range of order management applications.</p>
<p>Let me know what you think.<br />
-g</p>
</div>
        </div>

    </li><!-- #comment-## -->
    <li class="comment odd alt thread-even depth-1" id="dsq-comment-14">
        <div id="dsq-comment-header-14" class="dsq-comment-header">
            <cite id="dsq-cite-14">
                <span id="dsq-author-user-14">g</span>
            </cite>
        </div>
        <div id="dsq-comment-body-14" class="dsq-comment-body">
            <div id="dsq-comment-message-14" class="dsq-comment-message"><p>Since your spam filter seems to block my message: I posted some suggestions how this script could be used at the original thread in the magento forum.</p>
<p><a href="http://www.magentocommerce.com/boards/viewreply/264381/" rel="nofollow">http://www.magentocommerce.com/boards/viewreply/264381/</a></p>
</div>
        </div>

    </li><!-- #comment-## -->
    <li class="comment even thread-odd thread-alt depth-1" id="dsq-comment-54">
        <div id="dsq-comment-header-54" class="dsq-comment-header">
            <cite id="dsq-cite-54">
                <span id="dsq-author-user-54">Nick</span>
            </cite>
        </div>
        <div id="dsq-comment-body-54" class="dsq-comment-body">
            <div id="dsq-comment-message-54" class="dsq-comment-message"><p>Love the solution, but would like to expand on it a little further.</p>
<p>If I can get the csv file onto the server automatically, what about the possibilities to have this file run as a cronjob once a day. </p>
<p>So basically creating a module and in the etc/config.xml file you would have  </p>


<pre><code>
&lt;crontab&gt;
        &lt;jobs&gt;
            &lt;trackingimport&gt;
                &lt;schedule&gt;
                	&lt;!-- Run every day at 1830 --&gt;
                	&lt;cron_expr&gt;30 18 * * *&lt;/cron_expr&gt;
                &lt;/schedule&gt;
                &lt;run&gt;
                	&lt;model&gt;trackingimport/cron::fileimport&lt;/model&gt;
                &lt;/run&gt;
            &lt;/trackingimport&gt;
        &lt;/jobs&gt;
    &lt;/crontab&gt;
</code></pre>


<p>Then after this you would then create Model/Observer.php</p>
<p>In there would be the function that would look for the file in the directory.</p>
<p>so YMD.csv that way you could always just look for a file with year month day as the file name and not have to overwrite the existing file.</p>
<p>Let me know your thoughts on how this could be taken a step further.</p>
<p>Thanks,</p>
<p>Nick</p>
</div>
        </div>

    </li><!-- #comment-## -->
    <li class="comment odd alt thread-even depth-1" id="dsq-comment-55">
        <div id="dsq-comment-header-55" class="dsq-comment-header">
            <cite id="dsq-cite-55">
                <span id="dsq-author-user-55">Nick</span>
            </cite>
        </div>
        <div id="dsq-comment-body-55" class="dsq-comment-body">
            <div id="dsq-comment-message-55" class="dsq-comment-message"><p>Something like this would work?</p>


<pre><code>
&lt;?php

class Custom_TrackingImport_Model_Observer 
{
    /**
     * Extends the mass action select box to append the option to export to csv.
     * Event: core_block_abstract_prepare_layout_before
     */

    
    public function updateOrder($orderId, $email, $carrier, $trackingNum) 
    {
    
    if ($trackingfilecheck == true) {
    
    $includeComment = false;
    $comment = NULL;
 
    $order = Mage::getModel('sales/order')-&gt;loadByIncrementId($orderId);
 
    //This converts the order to &quot;Completed&quot;.
    $convertor = Mage::getModel('sales/convert_order');
    $shipment = $convertor-&gt;toShipment($order);
 
    foreach ($order-&gt;getAllItems() as $orderItem) {
 
        if (!$orderItem-&gt;getQtyToShip()) {
            continue;
        }
        if ($orderItem-&gt;getIsVirtual()) {
            continue;
        }
 
        $item = $convertor-&gt;itemToShipmentItem($orderItem);
 
        $qty = $orderItem-&gt;getQtyToShip();
 
        $item-&gt;setQty($qty);
        $shipment-&gt;addItem($item);
    }
 
    $carrierTitle = NULL;
 
    // READ MY COMMENTS REGARDING THIS SECTION ABOVE.
 
    if ($carrier == 'ups') {
        $carrierTitle = 'United Parcel Service';
    }
 
    if ($carrier == 'custom') {
        $carrierTitle = 'Parcel Force';
    }
 
    $data = array();
    $data['carrier_code'] = $carrier;
    $data['title'] = $carrierTitle;
    $data['number'] = $trackingNum;
 
    $track = Mage::getModel('sales/order_shipment_track')-&gt;addData($data);
    $shipment-&gt;addTrack($track);
 
    $shipment-&gt;register();
    $shipment-&gt;addComment($comment, $email &amp;&amp; $includeComment);
    $shipment-&gt;setEmailSent(true);
    $shipment-&gt;getOrder()-&gt;setIsInProcess(true);
 
    $transactionSave = Mage::getModel('core/resource_transaction')
        -&gt;addObject($shipment)
        -&gt;addObject($shipment-&gt;getOrder())
        -&gt;save();
 
    $shipment-&gt;sendEmail($email, ($includeComment ? $comment : ''));
    $order-&gt;setStatus('shipped');
    $shipment-&gt;save();
 
    return;
 
  }
}
  
  public function fileimport()
  {
  $fileName = 'LocateIT_'.date(&quot;Ymd&quot;).' .csv';
  $target_path = fopen(Mage::getBaseDir('export').'/LocateIT/'.$fileName, 'w');
  
  ini_set(&quot;auto_detect_line_endings&quot;, 1);
            $current_row = 1;
            $handle = fopen($target_path, &quot;r&quot;);
            $csvData = array();

  while ( ($data = fgetcsv($handle, 10000, &quot;,&quot;) ) !== FALSE )
            {
                $number_of_fields = count($data);
                if ($current_row == 1) {    //Header line
                    for ($c=0; $c &lt; $number_of_fields; $c++)
                    {
                        $header_array[$c] = $data[$c];
                    }
                } else {    //Data line
                    for ($c=0; $c &lt; $number_of_fields; $c++)
                    {
                        $data_array[$header_array[$c]] = $data[$c];
                    }
                    $csvData[] = $data_array;
                }
                $current_row++;
            }
 
            fclose($handle);

  foreach($csvData as $rec) {
              updateOrder($rec['OrderNumber'],
                                  $rec['Email'],
                                  $rec['Carrier'],
                                  $rec['TrackingNumber']);
  
  }
 }
}
</code></pre>


</div>
        </div>

    <ul class="children">
    <li class="comment byuser comment-author-tegan bypostauthor even depth-2" id="dsq-comment-56">
        <div id="dsq-comment-header-56" class="dsq-comment-header">
            <cite id="dsq-cite-56">
                <span id="dsq-author-user-56">tegan</span>
            </cite>
        </div>
        <div id="dsq-comment-body-56" class="dsq-comment-body">
            <div id="dsq-comment-message-56" class="dsq-comment-message"><p>Great idea Nick!</p>
<p>That would work. You could even schedule it as a cron job outside of magento for those who are not familiar with setting up cron jobs and models.</p>
<p>You could even adapt that methodology to look at an FTP for the file source. Might be real valuable way to grab the latest file for people who have setup a FTP server in their shipping department on the same computer that processes their inventory.</p>
</div>
        </div>

    </li><!-- #comment-## -->
</ul><!-- .children -->
</li><!-- #comment-## -->
    <li class="comment odd alt thread-odd thread-alt depth-1" id="dsq-comment-57">
        <div id="dsq-comment-header-57" class="dsq-comment-header">
            <cite id="dsq-cite-57">
                <span id="dsq-author-user-57">Nick</span>
            </cite>
        </div>
        <div id="dsq-comment-body-57" class="dsq-comment-body">
            <div id="dsq-comment-message-57" class="dsq-comment-message"><p>Hi Tegan,</p>
<p>I&#8217;ve sent you a module to your magento account, i&#8217;ve managed to forget to include the folder name as well as the module .xml file.</p>
<p>As well as that I had included a piece of code which sets status to shipped, which can be removed.</p>
<p>If possible would you be able to take a look at this for me, as i&#8217;m not getting an error from the code, but in the cron status is just remains as running.</p>
<p>If I remove the header row in the CSV the job completes, so not entirely sure what the problem is.</p>
</div>
        </div>

    <ul class="children">
    <li class="comment even depth-2" id="dsq-comment-2157">
        <div id="dsq-comment-header-2157" class="dsq-comment-header">
            <cite id="dsq-cite-2157">
                <span id="dsq-author-user-2157">Sofiane</span>
            </cite>
        </div>
        <div id="dsq-comment-body-2157" class="dsq-comment-body">
            <div id="dsq-comment-message-2157" class="dsq-comment-message"><p>Hello Nick, can you please publish the Module link, so we can have a look on the Class used for it?<br />
is there a way to maybe loose the email field ,so the file will take email address from order details in magento???? </p>
<p>Many Thanks</p>
</div>
        </div>

    </li><!-- #comment-## -->
</ul><!-- .children -->
</li><!-- #comment-## -->
    <li class="comment odd alt thread-even depth-1" id="dsq-comment-96">
        <div id="dsq-comment-header-96" class="dsq-comment-header">
            <cite id="dsq-cite-96">
                <span id="dsq-author-user-96">hisham</span>
            </cite>
        </div>
        <div id="dsq-comment-body-96" class="dsq-comment-body">
            <div id="dsq-comment-message-96" class="dsq-comment-message"><p>Does this works with a csv exported from 1.3 and imported back to 1.4?</p>
</div>
        </div>

    <ul class="children">
    <li class="comment byuser comment-author-tegan bypostauthor even depth-2" id="dsq-comment-97">
        <div id="dsq-comment-header-97" class="dsq-comment-header">
            <cite id="dsq-cite-97">
                <span id="dsq-author-user-97">tegan</span>
            </cite>
        </div>
        <div id="dsq-comment-body-97" class="dsq-comment-body">
            <div id="dsq-comment-message-97" class="dsq-comment-message"><p>Yes this should work</p>
</div>
        </div>

    </li><!-- #comment-## -->
</ul><!-- .children -->
</li><!-- #comment-## -->
    <li class="comment odd alt thread-odd thread-alt depth-1" id="dsq-comment-155">
        <div id="dsq-comment-header-155" class="dsq-comment-header">
            <cite id="dsq-cite-155">
                <span id="dsq-author-user-155">mar</span>
            </cite>
        </div>
        <div id="dsq-comment-body-155" class="dsq-comment-body">
            <div id="dsq-comment-message-155" class="dsq-comment-message"><p>Anyone know of a way to add an additional tracking number to an existing shipment. Trying to implement this script but once shipment is created i cant seem to find a way to just add the extra tracking number.</p>
</div>
        </div>

    </li><!-- #comment-## -->
    <li class="post pingback">
        <p>Pingback: <a href='http://kittenvillage.com/2011/04/27/magento-import/' rel='external nofollow' class='url'>Magento Import | Kitten Village</a>()</p>
    </li>
    </li><!-- #comment-## -->
    <li class="comment even thread-even depth-1" id="dsq-comment-2155">
        <div id="dsq-comment-header-2155" class="dsq-comment-header">
            <cite id="dsq-cite-2155">
                <span id="dsq-author-user-2155">Sofiane</span>
            </cite>
        </div>
        <div id="dsq-comment-body-2155" class="dsq-comment-body">
            <div id="dsq-comment-message-2155" class="dsq-comment-message"><p>Hello dear  tegan,</p>
<p>First i would say 1000000000 Thanks to you as it has really worked for me in first try????? just use manual_import.php instead to use form.php,,, and was runing smooth,,, mate many thanks .. and hope we can develop it and make is as module, so everybody can use it in easy way,<br />
Thanks again</p>
</div>
        </div>

    <ul class="children">
    <li class="comment byuser comment-author-tegan bypostauthor odd alt depth-2" id="dsq-comment-5477">
        <div id="dsq-comment-header-5477" class="dsq-comment-header">
            <cite id="dsq-cite-5477">
                <span id="dsq-author-user-5477">tegan</span>
            </cite>
        </div>
        <div id="dsq-comment-body-5477" class="dsq-comment-body">
            <div id="dsq-comment-message-5477" class="dsq-comment-message"><p>Thanks Sofiane. I wrote this a few years ago, amazing that people still find uses for it <img src="http://www.tegdesign.com/wp-includes/images/smilies/icon_smile.gif" alt=":)" class="wp-smiley" /></p>
</div>
        </div>

    </li><!-- #comment-## -->
</ul><!-- .children -->
</li><!-- #comment-## -->
    <li class="comment even thread-odd thread-alt depth-1" id="dsq-comment-4760">
        <div id="dsq-comment-header-4760" class="dsq-comment-header">
            <cite id="dsq-cite-4760">
http://www.waxink.com                <span id="dsq-author-user-4760">Atanu Banerjee</span>
            </cite>
        </div>
        <div id="dsq-comment-body-4760" class="dsq-comment-body">
            <div id="dsq-comment-message-4760" class="dsq-comment-message"><p>Thanks for an wonderful coding. I am really appreciate your work. Please tell me one thing, If I have split orders like same order id, different products with different tracking details, then what should we do? As of now your code doesn&#8217;t  allow us to update with same order id again. How we solve this? any Idea???<br />
Thanks in advance.</p>
</div>
        </div>

    <ul class="children">
    <li class="comment byuser comment-author-tegan bypostauthor odd alt depth-2" id="dsq-comment-5475">
        <div id="dsq-comment-header-5475" class="dsq-comment-header">
            <cite id="dsq-cite-5475">
                <span id="dsq-author-user-5475">tegan</span>
            </cite>
        </div>
        <div id="dsq-comment-body-5475" class="dsq-comment-body">
            <div id="dsq-comment-message-5475" class="dsq-comment-message"><p>Atanu In order to that you would need some sort of way to split the shipments. Unirgy has a amazing product called uDropship that could help you out.</p>
</div>
        </div>

    </li><!-- #comment-## -->
</ul><!-- .children -->
</li><!-- #comment-## -->
    <li class="comment even thread-even depth-1" id="dsq-comment-4813">
        <div id="dsq-comment-header-4813" class="dsq-comment-header">
            <cite id="dsq-cite-4813">
                <span id="dsq-author-user-4813">satu</span>
            </cite>
        </div>
        <div id="dsq-comment-body-4813" class="dsq-comment-body">
            <div id="dsq-comment-message-4813" class="dsq-comment-message"><p>i m new in magento so pls help me urgentlyyy<br />
how to solve this error :::::::Fatal error: Uncaught exception ‘Mage_Core_Exception’ with message ‘Cannot create an empty shipment.’ in /var/www/vhosts/mysite.com/httpdocs/magento_16/app/Mage.php:550 Stack trace: #0 /var/www/vhosts/mysite.com/httpdocs/magento_16/app/code/core/Mage/Sales/Model/Order/Shipment.php(504): Mage::throwException(‘Cannot create a…’) #1 /var/www/vhosts/mysite.com/httpdocs/magento_16/app/code/core/Mage/Core/Model/Abstract.php(304): Mage_Sales_Model_Order_Shipment-&gt;_beforeSave() #2 /var/www/vhosts/mysite.com/httpdocs/magento_16/app/code/core/Mage/Core/Model/Resource/Transaction.php(150): Mage_Core_Model_Abstract-&gt;save() #3 /var/www/vhosts/mysite.com/httpdocs/magento_16/test.php(60): Mage_Core_Model_Resource_Transaction-&gt;save() #4 /var/www/vhosts/mysite.com/httpdocs/magento_16/test.php(113): updateOrder(’100000010′, ‘test@googlema…’, ‘dhl’, ’555911′) #5 {main} thrown in /var/www/vhosts/mysite.com/httpdocs/magento_16/app/Mage.php on line 550 &#8211; See more at: <a href="http://www.tegdesign.com/importing-orders-into-magento-from-a-csv-using-php/#sthash.yCpaaOSn.dpuf" rel="nofollow">http://www.tegdesign.com/importing-orders-into-magento-from-a-csv-using-php/#sthash.yCpaaOSn.dpuf</a></p>
</div>
        </div>

    <ul class="children">
    <li class="comment byuser comment-author-tegan bypostauthor odd alt depth-2" id="dsq-comment-5478">
        <div id="dsq-comment-header-5478" class="dsq-comment-header">
            <cite id="dsq-cite-5478">
                <span id="dsq-author-user-5478">tegan</span>
            </cite>
        </div>
        <div id="dsq-comment-body-5478" class="dsq-comment-body">
            <div id="dsq-comment-message-5478" class="dsq-comment-message"><p>Can you post your entire code to a gist repositiory that would help me?</p>
</div>
        </div>

    </li><!-- #comment-## -->
</ul><!-- .children -->
</li><!-- #comment-## -->
    <li class="comment even thread-odd thread-alt depth-1" id="dsq-comment-5439">
        <div id="dsq-comment-header-5439" class="dsq-comment-header">
            <cite id="dsq-cite-5439">
http://www.abc.com                <span id="dsq-author-user-5439">Deft</span>
            </cite>
        </div>
        <div id="dsq-comment-body-5439" class="dsq-comment-body">
            <div id="dsq-comment-message-5439" class="dsq-comment-message"><p>I have an error:<br />
Fatal error: Uncaught exception &#8216;Mage_Core_Exception&#8217; with message &#8216;Cannot create an empty shipment.&#8217; in F:\xampp\htdocs\ext1\app\Mage.php:594 Stack trace: #0 F:\xampp\htdocs\ext1\app\code\core\Mage\Sales\Model\Order\Shipment.php(556): Mage::throwException(&#8216;Cannot create a&#8230;&#8217;) #1 F:\xampp\htdocs\ext1\app\code\core\Mage\Core\Model\Abstract.php(316): Mage_Sales_Model_Order_Shipment-&gt;_beforeSave() #2 F:\xampp\htdocs\ext1\app\code\core\Mage\Core\Model\Resource\Transaction.php(151): Mage_Core_Model_Abstract-&gt;save() #3 F:\xampp\htdocs\ext1\form.php(61): Mage_Core_Model_Resource_Transaction-&gt;save() #4 F:\xampp\htdocs\ext1\form.php(117): updateOrder(NULL, NULL, NULL, NULL) #5 {main} thrown in F:\xampp\htdocs\ext1\app\Mage.php on line 594</p>
</div>
        </div>

    <ul class="children">
    <li class="comment byuser comment-author-tegan bypostauthor odd alt depth-2" id="dsq-comment-5476">
        <div id="dsq-comment-header-5476" class="dsq-comment-header">
            <cite id="dsq-cite-5476">
                <span id="dsq-author-user-5476">tegan</span>
            </cite>
        </div>
        <div id="dsq-comment-body-5476" class="dsq-comment-body">
            <div id="dsq-comment-message-5476" class="dsq-comment-message"><p>Looking at your stack trace I can see that you didn&#8217;t pass a value to the updateOrder function. Notice your stack trace says updateOrder(NULL, NULL</p>
</div>
        </div>

    </li><!-- #comment-## -->
</ul><!-- .children -->
</li><!-- #comment-## -->
    <li class="comment even thread-even depth-1" id="dsq-comment-5657">
        <div id="dsq-comment-header-5657" class="dsq-comment-header">
            <cite id="dsq-cite-5657">
                <span id="dsq-author-user-5657">Sudeep</span>
            </cite>
        </div>
        <div id="dsq-comment-body-5657" class="dsq-comment-body">
            <div id="dsq-comment-message-5657" class="dsq-comment-message"><p>Can I have the Invoicing which can be also done so that Order Shows me completed.</p>
</div>
        </div>

    </li><!-- #comment-## -->
    <li class="comment odd alt thread-odd thread-alt depth-1" id="dsq-comment-5676">
        <div id="dsq-comment-header-5676" class="dsq-comment-header">
            <cite id="dsq-cite-5676">
                <span id="dsq-author-user-5676">Php Developer</span>
            </cite>
        </div>
        <div id="dsq-comment-body-5676" class="dsq-comment-body">
            <div id="dsq-comment-message-5676" class="dsq-comment-message"><p>Hi.. I am new to magento&#8230;I have to import orders of 1.4 version into 1.8 version.can you please tell me where i have to keep these files.or how can I do? please reply as soon as possible.</p>
</div>
        </div>

    </li><!-- #comment-## -->
    <li class="comment even thread-even depth-1" id="dsq-comment-5678">
        <div id="dsq-comment-header-5678" class="dsq-comment-header">
            <cite id="dsq-cite-5678">
                <span id="dsq-author-user-5678">Hernan</span>
            </cite>
        </div>
        <div id="dsq-comment-body-5678" class="dsq-comment-body">
            <div id="dsq-comment-message-5678" class="dsq-comment-message"><p>Hi tegan! Thank you so much for taking the time to share this code. I am running Magento 1.7, I have uploaded the .zip file, and change my carrier code, made sure that the call to Mage is ok. I can run the manual_import, upload the file, and receive a message that upload was successful. Though nothing changes on the backend.<br />
I don&#8217;t receive any errors. Can you please help if possible? Thank you again!</p>
</div>
        </div>

    </li><!-- #comment-## -->
    <li class="comment odd alt thread-odd thread-alt depth-1" id="dsq-comment-5680">
        <div id="dsq-comment-header-5680" class="dsq-comment-header">
            <cite id="dsq-cite-5680">
                <span id="dsq-author-user-5680">Selim Hasan</span>
            </cite>
        </div>
        <div id="dsq-comment-body-5680" class="dsq-comment-body">
            <div id="dsq-comment-message-5680" class="dsq-comment-message"><p>Hi Tegan, Greetings. Its seems this wonderful script does not compatible with latest magento (i am using 1.8.X) . Here i post the code,Please review and advised me. Thanks much.</p>
<p>The file  has been uploaded succesfully!</p>
<p>&lt;?php	</p>
<p>ini_set(&quot;auto_detect_line_endings&quot;, 1); </p>
<p>$current_row = 1; </p>
<p>$handle = fopen($target_path, &quot;r&quot;); </p>
<p>$csvData = array();</p>
<p>while ( ($data = fgetcsv($handle, 10000, &quot;,&quot;) ) !== FALSE ) </p>
<p>{ </p>
<p>$number_of_fields = count($data); </p>
<p>if ($current_row == 1) { //Header line </p>
<p>for ($c=0; $c &lt; $number_of_fields; $c++) </p>
<p>{ </p>
<p>$header_array[$c] = $data[$c];</p>
<p>} </p>
<p>} else { //Data line </p>
<p>for ($c=0; $c loadByIncrementId($rec['TrackingNumber']);</p>
<p>//This converts the order to &#8220;Completed&#8221;.</p>
<p>$convertor = Mage::getModel(&#8216;sales/convert_order&#8217;);</p>
<p>$shipment = $convertor-&gt;toShipment($order);</p>
<p>foreach ($order-&gt;getAllItems() as $orderItem) {</p>
<p>if (!$orderItem-&gt;getQtyToShip()) {</p>
<p>continue;</p>
<p>}</p>
<p>if ($orderItem-&gt;getIsVirtual()) {</p>
<p>continue;</p>
<p>}</p>
<p>$item = $convertor-&gt;itemToShipmentItem($orderItem);</p>
<p>$qty = $orderItem-&gt;getQtyToShip();</p>
<p>$item-&gt;setQty($qty);</p>
<p>$shipment-&gt;addItem($item);</p>
<p>}</p>
<p>$carrierTitle = NULL;</p>
<p>// FOR GUIDANCE ON THIS SECTION LOOK AT MY FIRST POST&#8230; IT HAS BETTER COMMENTS</p>
<p>if ($rec['Carrier'] == &#8216;ups&#8217;) {</p>
<p>$carrierTitle = &#8216;United Parcel Service';</p>
<p>}</p>
<p>if ($rec['Carrier'] == &#8216;usps&#8217;) {</p>
<p>$carrierTitle = &#8216;United States Postal Service';</p>
<p>}</p>
<p>if ($rec['Carrier'] == &#8216;flatrate&#8217;) {</p>
<p>$carrierTitle = &#8216;Some other carrier';</p>
<p>}</p>
<p>$data = array();</p>
<p>$data['carrier_code'] = $rec['Carrier'];</p>
<p>$data['title'] = $carrierTitle; </p>
<p>$data['number'] = $rec['TrackingNumber'];</p>
<p>$track = Mage::getModel(&#8216;sales/order_shipment_track&#8217;)-&gt;addData($data);</p>
<p>$shipment-&gt;addTrack($track);</p>
<p>$shipment-&gt;register();</p>
<p>$shipment-&gt;addComment($comment, $rec['Email'] &amp;&amp; $includeComment);</p>
<p>$shipment-&gt;setEmailSent(true);</p>
<p>$shipment-&gt;getOrder()-&gt;setIsInProcess(true);</p>
<p>$transactionSave = Mage::getModel(&#8216;core/resource_transaction&#8217;)</p>
<p>-&gt;addObject($shipment)</p>
<p>-&gt;addObject($shipment-&gt;getOrder())</p>
<p>-&gt;save();</p>
<p>$shipment-&gt;sendEmail($rec['Email'], ($includeComment ? $comment : &#8221;));</p>
<p>$shipment-&gt;save();</p>
<p>}</p>
<p>} //end if statment for file upload check</p>
<p>} else { // end if statement for post check for upload</p>
<p>?&gt;</p>
<p>CSV File:</p>
</div>
        </div>

    </li><!-- #comment-## -->
            </ul>


        </div>

    </div>

<script>
var disqus_url = 'http://www.tegdesign.com/importing-orders-into-magento-from-a-csv-using-php/';
var disqus_identifier = '81 http://www.tegdesign.com/?p=81';
var disqus_container_id = 'disqus_thread';
var disqus_shortname = 'tegdesign';
var disqus_title = "Importing Orders Into Magento from a CSV Using PHP";
var disqus_config_custom = window.disqus_config;
var disqus_config = function () {
    /*
    All currently supported events:
    onReady: fires when everything is ready,
    onNewComment: fires when a new comment is posted,
    onIdentify: fires when user is authenticated
    */
    
    
    this.language = '';
        this.callbacks.onReady.push(function () {

        // sync comments in the background so we don't block the page
        var script = document.createElement('script');
        script.async = true;
        script.src = '?cf_action=sync_comments&post_id=81';

        var firstScript = document.getElementsByTagName('script')[0];
        firstScript.parentNode.insertBefore(script, firstScript);
    });
    
    if (disqus_config_custom) {
        disqus_config_custom.call(this);
    }
};

(function() {
    var dsq = document.createElement('script'); dsq.type = 'text/javascript';
    dsq.async = true;
    dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
    (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
})();
</script>

	  


</div>

</div>

</div>

@endsection