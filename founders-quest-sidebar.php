<?

if ( is_user_logged_in() ) :
$boxes = array( 
  'box1' => array (
    'description' => array (
      'name' => 'Welcome + Introduction',
            'url' => 'congratulations-and-welcome',
            'id' => '1856',
    ),
  ),

  'box2' => array (

    'description' => array (
      'name' => 'The You Factor',
            'url' => 'the-you-factor',
            'id' => '1884',
    ),

    'links' => array (
            array (
        'name' => '7 Traits of an Entrepreneur',
                'url' => '7-traits-of-an-entrepreneur',
                'id' => 1866,
            ),

            array (
              'name' => 'Entrepreneurial Survey',
                'url' => 'personal-risk-profile',
                'id' => 1904,
            ),

            array (
              'name' => 'Ideal Life',
              'url' => 'your-ideal-life-what-is-it',
                'id' => 1869,
            ),

					 array (
					         'name' => 'Passion',
					           'url' => 'passion-what-you-love',
					           'id' => '1871',
					     ),

					 array (
					         'name' => 'Natural Talents & Gifts',
					           'url' => 'natural-talents-gifts',
					           'id' => 1873,
					       ),

					 array (
					         'name' => 'Experiences',
					           'url' => 'experiences',
					           'id' => 1875,
					       ),

            array (
              'name' => 'Work Style',
                'url' => 'work-style',
                'id' => 1877,
      ),

            array (
              'name' => 'Intersection Equals Heaven',
                'url' => 'intersection-equals-heaven',
                'id' => 1880
          ),
    ),
  ),

    'box3' => array (
    'description' => array (
            'name' => 'Giant Why', 
            'url' => 'the-purpose-room', 
            'id' => '1886',
    ),

    'links' => array (
            array (
        'name' => 'Purpose',
                'url' => 'purpose',
                'id' => 1950,
            ),
            array (
        'name' => 'Core Values',
                'url' => 'core-values',
                'id' => 1952,
            ),
            array (
        'name' => 'Clarity of Mission & Vision',
                'url' => 'clarity-of-mission-vision',
                'id' => 1954,
            ),
            array (
        'name' => 'Culture',
                'url' => 'culture',
                'id' => 1957,
            ),
        ),
  ),

    'box4' => array (
    'description' => array (
          'name' => 'Business Model Canvas',
            'url' => 'business-model-canvas',
            'id' => 2938,
        ),

    'links' => array (
            array (
        'name' => '9 Building Blocks Overview',
                'url' => '9-building-blocks-overview',
                'id' => 2940,
            ),
            array (
        'name' => 'Business Model Types',
                'url' => 'business-model-types',
                'id' => 2942,
            ),
            array (
        'name' => 'Secret Formula Triangle',
                'url' => 'secret-formula-triangle'
                'id' => 2946,
            ),
    ),
     ),

    'box5' => array (
    'description' => array (
            'name' => 'Customer Palette',
      'url' => 'customer-palette',
            'id' => 2948,
    ),

    'links' => array (
            array (
        'name' => 'Customer Segments',
                'url' => 'customer-segments',
                'id' => 2950,
            ),
            array (
        'name' => 'Perfect Customer Profile',
                'url' => 'perfect-customer-profile',
                'id' => 2952,
            ),
        ),
  ),

    'box6' => array (
    'description' => array (
            'name' => 'Discovery Center',
            'url' => 'discovery-center',
            'id' => 1892,
    ),

    'links' => array (
            array (
        'name' => 'Markets Are Conversations',
                'url' => 'markets-are-conversations',
                'id' => 1977,
            ),
            array (
        'name' => 'Feedback Loops',
                'url' => 'feedback-loops',
                'id' => 1979,
            ),
            array (
        'name' => 'VP Canvas (Pains/Gains/Jobs)',
                'url' => 'vp-canvas-painsgainsjobs',
                'id' => 1981,
            ),
            array (
        'name' => 'Platform',
                'url' => 'platform',
                'id' => 1983,
            ),
        ),
  ),

    'box7' => array (
    'description' => array (
      'name' => 'Value Proposition Module',
            'url' => 'value-proposition-module',
            'id' => 1898,
    ),

    'links' => array (
            array (
        'name' => 'VP Canvas (Solution Side)',
                'url' => 'vp-canvas-solution-side',
                'id' => 1987,
            ),
            array (
        'name' => 'Test, Measure, Learn, Discern',
                'url' => 'test-measure-learn-discern',
                'id' => 1985,
            ),
            array (
        'name' => 'Lean Product Development',
                'url' => 'lean-product-development',
                'id' => 1989,
            ),
            array (
        'name' => 'Lean Marketing',
                'url' => 'lean-marketing',
                'id' => 1991,
            ),
            array (
        'name' => 'Stink Bug Words',
                'url' => 'stink-bug-words',
                'id' => 1993,
            ),
        ),
  ),    

    'box8' => array (
    'description' => array (
          'name' => 'Revenue Room',
            'url' => 'revenue-room',
            'id' => 1900,
    ),

    'links' => array (
            array (
        'name' => 'Revenue Streams',
                'url' => 'revenue-streams',
                'id' => 1999,
            ),
            array (
        'name' => 'Customer Funnel',
                'url' => 'customer-funnel',
                'id' => 2001,
            ),
        ),
  ),

    'box9' => array (
    'description' => array (
          'name' => 'Bonus Section',
            'url' => 'bonus-section',
            'id' => 2172,
    ),

    'links' => array (
            array (
        'name' => '9 Building Blocks Expanded',
                'url' => '9-building-blocks-expanded',
                'id' => 2214,
								'sublinks'=> array (
										array (

											'name' => 'Customer Segments',
											'url' => 'customer-segments',
											'id' => 3171,
										),

										array (
											'name' => 'Value Propositions',
											'url' => 'value-propositions',
											'id' => 3173,
										),

										array (
											'name' => 'Channels',
											'url' => 'channels',
											'id' => 3184
										),
										array (
											'name' => 'Customer Relationships',
											'url' => 'customer-relationships',
											'id' => 3187
										),
										array (
											'name' => 'Revenue Streams',
											'url' => 'revenue-streams',
											'id' => 3190
										),
										array (
											'name' => 'Key Resources',
											'url' => 'key-resources',
											'id' => 3192
										),
										array (
											'name' => 'Key Activities',
											'url' => 'key-activities',
											'id' => 3194
										),
										array (
											'name' => 'Key Partnerships',
											'url' => 'key-partnerships',
											'id' => 3196
										),
										array (
											'name' => 'Cost Structure',
											'url' => 'cost-structure',
											'id' => 3198
										),
								),
            ),
        ),
    ),

    'box10' => array (
    'description' => array (
          'name' => '<strong>Private VIP Room:</strong> ',
            'url' => '/private-vip-room/',
            'id' => 2175,
    ),
  ),


);

echo '<ul>';

foreach ($boxes as $box) {
	$base = 'http://mightywisemedia.com/foundersquest';
	echo '<div class="both">
  <li class="main id_' . $box['description']['id'];
	if (is_page($box['description']['id']))
	{ 
		echo " current"; 
	} 
	if (empty($box['links'])) 
	{
		echo " no_children"; 
	}
	else
	{
		echo " has_children";
	}
	if (is_tree($box['description']['id']))
	{
		echo " toggled"; 
	}
	echo '">';
	if (!empty($box['links'])) 
	{ 
		echo '<span class="arrow"></span>';
	}
  
	echo '<a href="' . $base . '/' . $box['description']['url']  . '">';

	echo $box['description']['name'] . '</a></li>';

	if (!empty($box['links']))
	{
		echo "<ul";
		if ( ! is_tree($box['description']['id'])) 
		{ 
			echo ' class="hidden"';
		}
		echo ">";

		foreach ($box['links'] as $key=>$text)
		{
      echo '<li class="sub';
			if ( is_page($text['id'])) 
			{ 
				echo " current";
			} 
			echo '"><a href="' . $base . '/' . $box['description']['url'] . '/' . $text['url'] . '">';
			echo "&bull; " . $text['name'] . '</a></li>';

			if ( isset( $text['sublinks'] ) )
			{
				echo '<ul>';
				foreach ( $text['sublinks'] as $sublink )
				{
					echo '<li><a href="' . $base . '/' . $box['description']['url'] . '/' . $text['url'] . '/' . $sublink['url'] . '/">' . $sublink['name'] . '</a></li>';
				}
				echo '</ul>';

			}
    }
      echo "</ul>";
  }
    echo "</div>";
}
echo '</ul>';
endif;
