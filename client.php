<?php        
include 'creds.php';

/***********************************************************
 *
 * Required settings This section can be filled out is there is an issue with the include of creds.php above
 *
 ***********************************************************
 *
define("client", "mytestclient"); //Testclient
define("domain", 'http://api.brafton.com/'); //api domain
define("brafton_apiKey", "b3a53213-d43e-46d2-b6d7-f5a543ae129b"); //api key
define("hub_apiKey","3fceeb95-94b1-46fe-8333-227fef0ef318"); //hubspit key
define("blog_id","2826706745"); //blog id
define("author_id","2808057364"); //author of articles
define("portal","537449"); //hub portal id

define("image_import", false); // change to true to upload images to clients hubspot account 
define("image_folder", "brafton_images"); //folder to upload images to defaults to brafton_images.  Creates the folder if it does not exsist
 **********************************************************************************
 *
 * Video Settings
 *
 **********************************************************************************
 

// Video Settings
define("import_video", false);
define("brafton_video_publicKey", "");
define("brafton_video_secretKey", "");

//comment out the video player that are NOT used
//define("video_player", 'atlantis');
//define("video_player", 'videojs');
define("video_player", 'none');
*/
//require_once 'BraftonLibrary/BraftonLibrary.php';

require_once '../Hubspot-COS/Libraries.php';
//override methods
class HubspotImporter extends BraftonImporter{
    
}
    if(!check_blog_id()){
        echo 'blog id not set or incorrect<br/><br/>';
         $params = array(
             'hapikey'=>hub_apiKey,
        );

        list_blogs($params);
        } else {
        //current date in milliseconds
        $post_time = time()*1000;

        //subtracting 30 days *24 hrs *60 Minutes *60 Seconds *1000 Milliseconds
        $post_time -= 30*24*60*60*1000;

        $params = array(
            'hapikey'=>hub_apiKey,
            'content_group_id'=>blog_id,
            'created__gte'=>$post_time,
            'limit'=>50,
            'order_by'=>'-created'
        );

        $titles = list_post_titles($params);

        $existing_topics = list_topics();
        echo 'Importing';
        $import = new HubspotImporter();
        $error = new BraftonErrorReport();
        if(brafton_apiKey != '' || brafton_apiKey != null){
            $import->import_articles($titles,$existing_topics);
        }
        if(import_video){
            $import->import_videos($titles,$existing_topics);
        }
	}
?>