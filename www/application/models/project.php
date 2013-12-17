<?php

/**
 * Template DataMapper Model
 *
 * Use this basic model as a template for creating new models.
 * It is not recommended that you include this file with your application,
 * especially if you use a Template library (as the classes may collide).
 *
 * To use:
 * 1) Copy this file to the lowercase name of your new model.
 * 2) Find-and-replace (case-sensitive) 'Template' with 'Your_model'
 * 3) Find-and-replace (case-sensitive) 'template' with 'your_model'
 * 4) Find-and-replace (case-sensitive) 'templates' with 'your_models'
 * 5) Edit the file as desired.
 *
 * @license		MIT License
 * @category	Models
 * @author		Phil DeJarnett
 * @link		http://www.overzealous.com
 */
class Project extends DataMapper
{
	// Uncomment and edit these two if the class has a model name that
	//   doesn't convert properly using the inflector_helper.
	// var $model = 'template';
	// var $table = 'templates';

	// You can override the database connections with this option
	// var $db_params = 'db_config_name';

	// --------------------------------------------------------------------
	// Relationships
	//   Configure your relationships below
	// --------------------------------------------------------------------

	// Insert related models that Template can have just one of.
	var $has_one = array();

	// Insert related models that Template can have more than one of.
	var $has_many = array();

	/* Relationship Examples
	 * For normal relationships, simply add the model name to the array:
	 *   $has_one = array('user'); // Template has one User
	 *
	 * For complex relationships, such as having a Creator and Editor for
	 * Template, use this form:
	 *   $has_one = array(
	 *   	'creator' => array(
	 *   		'class' => 'user',
	 *   		'other_field' => 'created_template'
	 *   	)
	 *   );
	 *
	 * Don't forget to add 'created_template' to User, with class set to
	 * 'template', and the other_field set to 'creator'!
	 *
	 */

	// --------------------------------------------------------------------
	// Validation
	//   Add validation requirements, such as 'required', for your fields.
	// --------------------------------------------------------------------

	var $validation = array(
		'name' => array(
			'rules' => array('required'),
			'label' => 'Project Name'
		),
        'url_key' => array(
            'rules' => array('required'),
            'label' => 'URL Key'
        ),
        'intro' => array(
            'rules' => array('required'),
            'label' => 'Intro Text'
        ),
        'description' => array(
            'rules' => array('required'),
            'label' => 'Description Text'
        ),
        'tags' => array(
            'rules' => array('required'),
            'label' => 'Tags'
        ),
	);

	// --------------------------------------------------------------------
	// Default Ordering
	//   Uncomment this to always sort by 'name', then by
	//   id descending (unless overridden)
	// --------------------------------------------------------------------

	// var $default_order_by = array('name', 'id' => 'desc');

	// --------------------------------------------------------------------

	/**
	 * Constructor: calls parent constructor
	 */
    function __construct($id = NULL)
	{
		parent::__construct($id);
    }

	// --------------------------------------------------------------------
	// Post Model Initialisation
	//   Add your own custom initialisation code to the Model
	// The parameter indicates if the current config was loaded from cache or not
	// --------------------------------------------------------------------
	function post_model_init($from_cache = FALSE)
	{
	}

	// --------------------------------------------------------------------
	// Custom Methods
	//   Add your own custom methods here to enhance the model.
	// --------------------------------------------------------------------

	/* Example Custom Method
	function get_open_templates()
	{
		return $this->where('status <>', 'closed')->get();
	}
	*/
    
    // For Javascript search.
    private $searchKey;
    /**
    * Return a search string containing all the searchable text for this project.
    */
    public function get_search_key()
    {
        if (!strlen($this->searchKey))
            $this->searchKey = $this->name . ' ' . join(' ', array_map('trim', explode(',', $this->tags))) . $this->intro . ' ' . $this->description . ' ' . $this->quote;
        return $this->searchKey;
    }

    // Split the description field into the left and right columns.    
    const DESCRIPTION_COLUMN_SEPARATOR = '[column_break]';
    private $descCols;
    /**
    * Return the left or right column of the description.
    * 
    * @param mixed $position
    */
    public function get_description_column($position = 0)
    {
        if (!count($this->descCols))
            $this->descCols = explode(Project::DESCRIPTION_COLUMN_SEPARATOR, $this->description);
        // Protect against the admin user accidentally excluding the column separator.
        if ($position > count($this->descCols) - 1)
            return '';
        return $this->descCols[$position];
    }
    
    // Split the resource_links field into an array of hyperlinks.
    const RESOURCE_LINKS_COLUMN_SEPARATOR = ',';
    const RESOURCE_LINKS_ROW_SEPARATOR = PHP_EOL;
    private $resourceLinksArray = null;
    /**
    * Return an array of resource_links hyperlinks.
    */
    public function get_resource_links_array()
    {
        if (!$this->resourceLinksArray)
        {
            $this->resourceLinksArray = array();
            if (strlen($this->resource_links))
            {
                foreach (explode(Project::RESOURCE_LINKS_ROW_SEPARATOR, $this->resource_links) as $row)
                {
                    if (!strlen(trim($row))) // Ignore blank lines.
                        continue;
                    $colSepPos = strpos($row, Project::RESOURCE_LINKS_COLUMN_SEPARATOR);
                    array_push($this->resourceLinksArray, '&#155; <a href="' . trim(substr($row, 0, $colSepPos)) . '" target="_blank">' . trim(substr($row, $colSepPos + 1) . '</a>'));
                }
            }
        }
        return $this->resourceLinksArray;
    }
    
    /**
    * Perform a MySQL search for the term. If $fts = true then do a full-text search. Otherwise do a LIKE %...% search.
    * 
    * @param mixed $str
    * @param mixed $fts
    * @return DataMapper
    */
    public function search($str, $fts = false)
    {
        $str = trim($str);

        /* Search logic change!
        // No blank search strings.
        if (!strlen($str))
            throw new Exception('Cannot call Project::search() with a blank string!');

        if ($fts)
        {
            // Parse the search string into a format usable for FTS.
            $str = '+' . preg_replace('/\s+/', ' +', $str);

            // Run the FTS query.
            $searchSql = 'SELECT * FROM projects WHERE MATCH(`name`, tags, intro, description, `quote`) AGAINST(? IN BOOLEAN MODE) AND enabled = TRUE;';
            $params = array($str); // Params are automatically escaped.
            return $this->query($searchSql, $params);
        }
        else
        {
            // Run a LIKE query.
            return $this->group_start()
                ->like('name', $str)
                ->or_like('tags', $str)
                ->or_like('intro', $str)
                ->or_like('description', $str)
                ->or_like('quote', $str)
                ->group_end()
                ->where('enabled', TRUE)
                ->get();
        }
        */

        if (!strlen($str))
        {
            // If no search term is supplied, return all projects.
            return $this->order_by('name', 'ASC')->get_where(array('enabled' => true));
        }
        elseif ($fts)
        {
            // Parse the search string into a format usable for FTS.
            $str = '+' . preg_replace('/\s+/', ' +', $str);

            // Run the FTS query.
            $searchSql = 'SELECT * FROM projects WHERE MATCH(`name`, tags, intro, description, `quote`) AGAINST(? IN BOOLEAN MODE) AND enabled = TRUE ORDER BY `name` ASC;';
            $params = array($str); // Params are automatically escaped.
            return $this->query($searchSql, $params);
        }
        else
        {
            // Run a LIKE query.
            return $this->group_start()
                ->like('name', $str)
                ->or_like('tags', $str)
                ->or_like('intro', $str)
                ->or_like('description', $str)
                ->or_like('quote', $str)
                ->group_end()
                ->where('enabled', TRUE)
                ->order_by('name', 'ASC') // Hopefully this doesn't break anything.
                ->get();
        }
    }
    
	// --------------------------------------------------------------------
	// Custom Validation Rules
	//   Add custom validation rules for this model here.
	// --------------------------------------------------------------------

	/* Example Rule
	function _convert_written_numbers($field, $parameter)
	{
	 	$nums = array('one' => 1, 'two' => 2, 'three' => 3);
	 	if(in_array($this->{$field}, $nums))
		{
			$this->{$field} = $nums[$this->{$field}];
	 	}
	}
	*/
}

/* End of file template.php */
/* Location: ./application/models/template.php */
