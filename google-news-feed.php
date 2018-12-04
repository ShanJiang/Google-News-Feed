<?php 

/**
* Plugin Name: Google News Feed
* Description:This plugin allows you to embed shortcode [google_news_feed] to you website, and allow you to display a search form to search for Google News.
* Version: 1.0
* Author: Shan Jiang
* Author URI: https://www.linkedin.com/in/ufshanjiang/
* License: GPLv2+
**/

	function add_plugin_scripts() {
		wp_enqueue_style( 'gnf-style', plugins_url().'/google-news-feed/gnf_style.css' );
		wp_enqueue_script( 'gnf-script', plugins_url().'/google-news-feed/gnf_script.js', array ( 'jquery' ), 1.1, true);	
	}
		add_action( 'wp_enqueue_scripts', 'add_plugin_scripts' );

	function gnf_admin_menu_option(){
		add_menu_page('Google News Feed', 'Google News', 'manage_options', 'gnf-admin-menu', 'gnf_scripts_page','dashicons-rss', 200);
	}
		add_action('admin_menu', 'gnf_admin_menu_option');
	
    function gnf_scripts_page(){

      ?>
    	<div class="wrap">
    		<h1>Google News Feed</h1>
    		<p>Author: <a href="https://www.linkedin.com/in/ufshanjiang/">Shan Jiang </a></p>
    		<p>Description: This is a plugin that allows you to embed shortcode to your website and display google search form for news feed. <br> Simply paste [google_news_feed] shortcode to your posts or pages, then it will display a search form searches the Google News XML/RSS Feed. </p>
			<h3>Screenshot of this plugin:</h3>
			<img src="<?php echo plugins_url().'/google-news-feed/gnf.JPG'?>">
    	</div>

    <?php
    }
    		function gnfsearchform(){

    			$searchTerm = "";
				if($_SERVER["REQUEST_METHOD"] == "POST"){
					$searchTerm = $_POST["searchTerm"];
				}

    			$content = '';
    			$content .='<div>
							<form method="post" id="google_news_feed" style="padding-left:18px;">
							<img style="padding-right: 1%;" src="https://www.gstatic.com/images/branding/googlelogo/svg/googlelogo_clr_74x24px.svg">
							<input type="text" id="searchTerm" name="searchTerm" placeholder="Search" style="border:1px solid #00539b;" value="'.$searchTerm.'">
							</form>
							<div class="col-md-10" id="output">';
						
				 $limit = "11";
			     $file = "http://news.google.com/news?q=".$searchTerm."&output=rss";

				if($xml = simplexml_load_file($file)){
				$content .='<ul>';
					foreach($xml->channel->item as $item)
					{
						$title = $item->title;
						$link = $item->link;
						$description = $item->description;
						$pubDate = $item->pubDate;

						$content .= '<li class="gnf">
						
						<p>'.$description.'</p>
						</li>';
						$limit--;
						if($limit == 0){break;}
					}
					
					$content .='</ul>';	
	                }
	           		else{
	           	   	echo "<p>Oops, the most recent news about ".$searchTerm." cannot be displayed at this time.</p>";
	           		}

	           		$content .='</div></div>';
					return $content;
    		}

    		add_shortcode('google_news_feed','gnfsearchform');

?>
