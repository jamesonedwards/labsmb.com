<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('BaseController.php');

class Home extends BaseController
{
    const PROJECT_DATE_FORMAT = 'm.d.y';
    const TWEET_DATE_FORMAT = 'M j';
    const TWEET_CACHE_ID = 'home_page_tweet';
    const TWEET_BACKUP_CACHE_ID = 'home_page_tweet_backup';
    const PINTEREST_DATE_FORMAT = 'm.d.y';
    const PINTEREST_CACHE_ID = 'home_page_pinterest';
    const PINTEREST_BACKUP_CACHE_ID = 'home_page_pinterest_backup';
    const TUMBLR_DATE_FORMAT = 'm.d.y';
    const TUMBLR_PHOTO_VIDEO_CACHE_ID = 'home_page_photo_video_tumblr';
    const TUMBLR_TEXT_CACHE_ID = 'home_page_text_tumblr';
    
    // Item counts for the four feeds on this page.
    const PROJECT_ITEM_COUNT = 3;
    const TWITTER_ITEM_COUNT = 1;
    const TUMBLR_ITEM_COUNT = 3;
    const PINTEREST_ITEM_COUNT = 4;
    
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Index Page for this controller.
     */
    public function index()
    {
        // TODO: Home page should have an easter egg that launches the dynamic logo Flash (or HTML5) app.
        
        // This determines which page we're on.
        $data['page'] = 'home_index';
        
        /////////////////////
        /// PROJECTS FEED ///
        /////////////////////

        $projectObj = new Project();
        $projectObj->where('enabled', true)->order_by('sort_order', 'ASC')->order_by('created', 'DESC')->get(Home::PROJECT_ITEM_COUNT);
        $data['projects'] = $projectObj;
        
        ////////////////////
        /// TWITTER FEED ///
        ////////////////////

        // Get most recent tweet for the configured hashtab, limited to users in the configured whitelist.
        // Example: https://api.twitter.com/1.1/search/tweets.json?q=%23labsmb+from:jameson_mb&result_type=recent&count=10
        $this->load->library('twitteroauth');
        $twitterConn = $this->twitteroauth->create(config_item('twitter_consumer_token'), config_item('twitter_consumer_secret'), config_item('twitter_access_token'), config_item('twitter_access_secret'));
        
        // Check for cache first.
        $tweet = $this->cache->get(Home::TWEET_CACHE_ID);
        $tweets = null;        

        // No cache so make API call.
        if (!$tweet)
        {
            $twitterApiQuery = urlencode(config_item('twitter_search_hashtag')) . '+' . join('+OR+', array_map('Home::format_user_for_twitter_api', config_item('twitter_whitelist')));
            $twitterApiParams = array(
                'q' => $twitterApiQuery,
                'result_type' => 'recent',
                'count' => Home::TWITTER_ITEM_COUNT
            );
            $tweets = $twitterConn->get('search/tweets', $twitterApiParams);
            
            if ($twitterConn->http_code == 200 && count($tweets->statuses))
            {
                // Grab the first tweet.
                $tweet = $tweets->statuses[0];

                // Cache result if successful.
                $this->cache->save(Home::TWEET_CACHE_ID, $tweet, config_item('tweet_cache_seconds'));
            }
            else
            {
                // The home page requires a tweet, but the Twitter search API only returns tweets up to a certain age. If we get no tweets from the search API, use another approach.
                $twitterApiParams = array(
                    'screen_name' => config_item('twitter_screen_name'),
                    'count' => Home::TWITTER_ITEM_COUNT
                );
                $tweets = $twitterConn->get('statuses/user_timeline', $twitterApiParams);
                
                if ($twitterConn->http_code == 200 && count($tweets))
                {
                    $tweet = $tweets[0];

                    // Cache result if successful.
                    $this->cache->save(Home::TWEET_CACHE_ID, $tweet, config_item('tweet_cache_seconds'));
                }
            }
        }

        $data['tweetDate'] = Home::format_tweet_date($tweet->created_at);
        
        // Add links to tweets.
        $data['tweetText'] = Home::format_tweet_text($tweet->text);

        //////////////////////
        /// PINTEREST FEED ///
        //////////////////////

        // Get the most recent pins from the labs pinterest account.
        $pinterestPins = null;
        $showPinterest = true;

        // We need to trap the 403 warning as an Exception so temporarily set the error handler.
        set_error_handler('handleErrorAsException');
        // Put some error handling in here, since it's pretty sketchy to rely on the Pinterst RSS HTML formatting...
        try
        {
            // Source: http://just-ask-kim.com/pinterest-rss-feed/#.UVXk81fNina
            //$pinterestApiUrl = 'http://pinterest.com/labsmb/feed.rss';
            $pinterestApiUrl = 'http://pinterest.com/labsmb/labs-inspiration.rss';
            $pinterestXml = $this->make_cached_request($pinterestApiUrl, Home::PINTEREST_CACHE_ID, config_item('pinterest_cache_seconds'));
            $pinterestPins = Home::parse_pinterest_feed($pinterestXml);

            // If there are no parsing errors, update the backup cache (if expired).
            if (!$this->cache->get(Home::PINTEREST_BACKUP_CACHE_ID))
                $this->cache->save(Home::PINTEREST_BACKUP_CACHE_ID, $pinterestXml, config_item('pinterest_backup_cache_seconds'));
        }
        catch (Exception $ex)
        {
            // If there's an exception, try the backup cache.
            try
            {
                $pinterestPins = Home::parse_pinterest_feed($this->cache->get(Home::PINTEREST_BACKUP_CACHE_ID));
            }
            catch (Exception $ex)
            {
                // Log error and continue.
                log_message('error', 'Pinterest feed error: ' . $ex->getMessage());
                $showPinterest = false;
            }
        }

        restore_error_handler();
        $data['showPinterest'] = $showPinterest;

        ///////////////////
        /// TUMBLR FEED ///
        ///////////////////

        // Get the most recent PHOTO posts from the labs tumblr account.
        $tumblrApiUrl = 'http://labsmb.tumblr.com/api/read/?num=' . (Home::TUMBLR_ITEM_COUNT * 3); // Only want photo and video posts, but get 3 times the desired number to be safe.
        $tumblrXml = $this->make_cached_request($tumblrApiUrl, Home::TUMBLR_PHOTO_VIDEO_CACHE_ID, config_item('tumblr_cache_seconds'));
        $tumblrXml = $this->make_cached_request($tumblrApiUrl, Home::TUMBLR_PHOTO_VIDEO_CACHE_ID, 0);
        $tumblrFeed = simplexml_load_string($tumblrXml);
        // To make the view a little cleaner, let's clean up the posts array here.
        $tumblrPhotoVideoPosts = array();
        $tumblrPhotoVideoPostCnt = 0;
        foreach ($tumblrFeed->posts->post as $post)
        {
            if ($post['type'] == 'photo' || $post['type'] == 'video')
            {
                array_push($tumblrPhotoVideoPosts, $post);
                $tumblrPhotoVideoPostCnt++;
                if ($tumblrPhotoVideoPostCnt >= Home::TUMBLR_ITEM_COUNT)
                    break; // Exit condition.
            }
        }
        $data['tumblrPhotoAndVideoPosts'] = $tumblrPhotoVideoPosts;
        
        // If we have don't get any Pinterst data, then show two columns of Tumblr data. Else show single column.
        if ($showPinterest)
        {
            $data['pinterestPins'] = $pinterestPins;
        }
        else
        {
            // Get the most recent TEXT posts from the labs tumblr account.
            $tumblrApiUrl = 'http://labsmb.tumblr.com/api/read/?type=regular&num=' . Home::TUMBLR_ITEM_COUNT;
            $tumblrXml = $this->make_cached_request($tumblrApiUrl, Home::TUMBLR_TEXT_CACHE_ID, config_item('tumblr_cache_seconds'));
            $tumblrFeed = simplexml_load_string($tumblrXml);
            $data['tumblrTextPosts'] = $tumblrFeed->posts;
        }

        // Load the view, passing in the data array.
        $this->load->view('home_index', $data);
    }
    
    public static function format_project_date($strDate)
    {
        return strlen($strDate) ? date(HOME::PROJECT_DATE_FORMAT, strtotime($strDate)) : '';
    }

    public static function format_tweet_date($strDate)
    {
        return strlen($strDate) ? date(HOME::TWEET_DATE_FORMAT, strtotime($strDate)) : '';
    }

    private static function format_tweet_text($text)
    {
        // Add hyperlinks.
        $text = preg_replace('#(http:\/\/[^ ]+)#i', '<a href="$1" target="_blank">$1</a>', $text);
        $text = preg_replace('#(@[^ ]+)#i', '<a href="http://twitter.com/$1" target="_blank">$1</a>', $text);
        return $text;
    }

    private static function format_user_for_twitter_api($username)
    {
        return 'from:' . $username;
    }

    public static function format_tumblr_date($timestamp)
    {
        return $timestamp > 0 ? date(HOME::TUMBLR_DATE_FORMAT, $timestamp) : '';
    }

    public static function format_pinterest_date($strDate)
    {
        return strlen($strDate) ? date(HOME::PINTEREST_DATE_FORMAT, strtotime($strDate)) : '';
    }

    private static function parse_pinterest_feed($xml)
    {
        if (!strlen($xml))
            throw new Exception('Pinterest feed is blank.');
        $pinterestFeed = simplexml_load_string($xml);
        $pinterestPins = array();
        $pinterestPinCnt = Home::PINTEREST_ITEM_COUNT;
        
        foreach ($pinterestFeed->channel->item as $item)
        {
            if ($pinterestPinCnt <= 0)
                break;
            // HACK: Parse data from HTML in description fields.
            $doc = new DOMDocument();
            $doc->loadHTML($item->description);
            array_push($pinterestPins, array(
                'img' => $doc->getElementsByTagName('img')->item(0)->getAttribute('src'),
                'text' => $doc->textContent,
                'pubDate' => (string)$item->pubDate,
                'url' => (string)$item->link));
            $pinterestPinCnt--;
            $doc = null;
        }
        
        return $pinterestPins;
    }
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */