<?php

class WP_42_Credits extends WP_Credits {

	function groups() {
		$groups = array(
			'project-leaders' => array(
				'name'    => 'Project Leaders',
				'type'    => 'titles',
				'shuffle' => true,
				'data'    => array(
					'matt'        => array( 'Matt Mullenweg', 'Cofounder, Project Lead' ),
					'nacin'       => array( 'Andrew Nacin',   'Lead Developer'          ),
					'markjaquith' => array( 'Mark Jaquith',   'Lead Developer', '097a87a525e317519b5ee124820012fb' ),
					'azaozz'      => array( 'Andrew Ozz',     'Lead Developer'          ),
					'helen'       => array( 'Helen Hou-Sandí','Lead Developer'          ),
					'dd32'        => array( 'Dion Hulse',     'Lead Developer'          ),
				),
			),
			'core-developers' => array(
				'name'    => 'Contributing Developers',
				'type'    => 'titles',
				'shuffle' => false,
				'data'    => array(
					'DrewAPicture'   => array( 'Drew Jaynes',       'Release Lead'   ),
					'ocean90'        => array( 'Dominik Schilling', 'Core Developer' ),
					'SergeyBiryukov' => array( 'Sergey Biryukov',   'Core Developer' ),
					'wonderboymusic' => array( 'Scott Taylor',      'Core Developer' ),
					'johnbillion'    => array( 'John Blackbourn',   'Core Developer', '0000ba6dd1b089e1746abbfe6281ee3b' ),
					'boonebgorges'   => array( 'Boone B. Gorges',   'Core Developer' ),
					'pento'          => array( 'Gary Pendergast',   'Core Developer' ),
					'ryan'           => 'Ryan Boren',
					'lancewillett'   => 'Lance Willett',
				),
			),
			'contributing-developers' => array(
				'name'    => false,
				'type'    => 'titles',
				'shuffle' => true,
				'data'    => array(
					'iseulde'        => 'Ella Iseulde Van Dorpe',
					'jorbin'         => 'Aaron Jorbin',
					'jeremyfelt'     => 'Jeremy Felt',
				),
			),
			'recent-rockstars' => array(
				'name'    => false,
				'type'    => 'titles',
				'shuffle' => true,
				'data'    => array(
					'stephdau'         => 'Stephane Daury',
					'michael-arestad'  => array( 'Michael Arestad', 'e8b4c8470f61ff15b9c98f7a1556c16b' ),
					'kraftbj'          => 'Brandon Kraft',
					'celloexpressions' => array( 'Nick Halsey', '42e659bb8c86851c230e527f8ce1764b'  ),
					'westonruter'      => array( 'Weston Ruter', '22ed378fbf1d918ef43a45b2a1f34634' ),
					'afercia'          => 'Andrea Fercia',
					'valendesigns'     => 'Derek Herman',
					'joedolson'        => 'Joe Dolson',
					'tyxla'            => 'Marin Atanasov'
				),
			),
		);
		return $groups;
	}

	// As of final release, r30800 to r308xx
	function props() {
		return array(
			'a5hleyrich',
			'aaroncampbell',
			'abhishekfdd',
			'adamsilverstein',
			'afercia',
			'alexkingorg',
			'ankit-k-gupta',
			'ankitgadertcampcom',
			'aramzs',
			'arminbraun',
			'ashfame',
			'atimmer',
			'avryl',
			'awbauer',
			'azaozz',
			'bananastalktome',
			'barrykooij',
			'batmoo',
			'beaulebens',
			'bendoh',
			'boonebgorges',
			'bswatson',
			'bueltge',
			'c3mdigital',
			'cais',
			'calevans',
			'carolinegeven',
			'caseypatrickdriscoll',
			'caspie',
			'cbaldelomar',
			'cdog',
			'celloexpressions',
			'cfinke',
			'cfoellmann',
			'cheffheid',
			'chipbennett',
			'chipx86',
			'chrico',
			'chriscct7',
			'clifgriffin',
			'clorith',
			'codix',
			'coffee2code',
			'collinsinternet',
			'corphi',
			'couturefreak',
			'craig-ralston',
			'cweiske',
			'cyman',
			'danielbachhuber',
			'davidakennedy',
			'davidanderson',
			'davideugenepratt',
			'davidhamiltron',
			'dd32',
			'deconf',
			'denis-de-bernardy',
			'desaiuditd',
			'designsimply',
			'desrosj',
			'dimadin',
			'dipeshkakadiya',
			'dkotter',
			'dlh',
			'dllh',
			'dmchale',
			'doublesharp',
			'drewapicture',
			'dsmart',
			'dzerycz',
			'earnjam',
			'ebinnion',
			'eliorivero',
			'elliottcarlson',
			'emazovetskiy',
			'enej',
			'ericlewis',
			'ethitter',
			'evansolomon',
			'extendwings',
			'f-j-kaiser',
			'fab1en',
			'fhwebcs',
			'filosofo',
			'floriansimeth',
			'folletto',
			'frankpw',
			'funkatronic',
			'gabrielperezs',
			'garyc40',
			'garyj',
			'geertdd',
			'genkisan',
			'georgestephanis',
			'grahamarmfield',
			'greglone',
			'gungeekatx',
			'hakre',
			'harishchaudhari',
			'hauvong',
			'helen',
			'herbmillerjr',
			'hew',
			'hissy',
			'hlashbrooke',
			'hnle',
			'horike',
			'hugobaeta',
			'iamtakashi',
			'iandunn',
			'ianmjones',
			'idealien',
			'imath',
			'ipm-frommen',
			'ipstenu',
			'iseulde',
			'ixkaito',
			'jacklenox',
			'jamescollins',
			'janhenckens',
			'jartes',
			'jcastaneda',
			'jdgrimes',
			'jeremyfelt',
			'jesin',
			'jfarthing84',
			'jipmoors',
			'jlevandowski',
			'joedolson',
			'joemcgill',
			'joen',
			'johnbillion',
			'johneckman',
			'johnjamesjacoby',
			'joostdekeijzer',
			'joostdevalk',
			'jorbin',
			'joshlevinson',
			'jphase',
			'jtsternberg',
			'juliobox',
			'justincwatt',
			'kadamwhite',
			'kevdotbadger',
			'kopepasah',
			'kovshenin',
			'kpdesign',
			'kraftbj',
			'krogsgard',
			'kucrut',
			'lamosty',
			'lancewillett',
			'leopeo',
			'lgladdy',
			'magicroundabout',
			'maimairel',
			'mako09',
			'marcelomazza',
			'marcochiesi',
			'markjaquith',
			'markoheijnen',
			'mattheu',
			'mattheweppelsheimer',
			'mattwiebe',
			'mattyrob',
			'maxcutler',
			'mboynes',
			'mdawaffe',
			'mdgl',
			'mehulkaklotar',
			'melchoyce',
			'meloniq',
			'mercime',
			'mgibbs189',
			'michael-arestad',
			'michalzuber',
			'mikehansenme',
			'mikengarrett',
			'mikeschinkel',
			'mindrun',
			'miqrogroove',
			'mkaz',
			'mmn-o',
			'momdad',
			'mordauk',
			'morganestes',
			'morpheu5',
			'mrahmadawais',
			'mzak',
			'nacin',
			'Nao',
			'nathan_dawson',
			'nbachiyski',
			'neil_pie',
			'nerrad',
			'netweb',
			'nicnicnicdevos',
			'nikv',
			'ninnypants',
			'nitkr',
			'nofearinc',
			'norcross',
			'nprasath002',
			'nunomorgadinho',
			'obenland',
			'ocean90',
			'originalexe',
			'oso96_2000',
			'pareshradadiya-1',
			'pathawks',
			'paulschreiber',
			'paulwilde',
			'pavelevap',
			'pbearne',
			'pento',
			'petemall',
			'peterwilsoncc',
			'podpirate',
			'postpostmodern',
			'prasoon2211',
			'r-a-y',
			'rachelbaker',
			'rahulbhangale',
			'ramiy',
			'ravindra-pal-singh',
			'redsweater',
			'rianrietveld',
			'ritteshpatel',
			'rmarks',
			'rodrigosprimo',
			'ryelle',
			'sagarjadhav',
			'samo9789',
			'samuelsidler',
			'scottgonzalez',
			'scribu',
			'seanchayes',
			'senff',
			'sergejmueller',
			'sergeybiryukov',
			'sevenspark',
			'sgrant',
			'sillybean',
			'simonwheatley',
			'siobhan',
			'sippis',
			'sirbrillig',
			'sivel',
			'slobodanmanic',
			'solarissmoke',
			'sorich87',
			'stephdau',
			'stevegrunwell',
			'stevehickeydesign',
			'stevenkword',
			'taka2',
			'thaicloud',
			'themiked',
			'thomaswm',
			'tillkruess',
			'timersys',
			'timothyblynjacobs',
			'tiqbiz',
			'tmatsuur',
			'tmeister',
			'tobiasbg',
			'tomdxw',
			'travisnorthcutt',
			'trepmal',
			'trishasalas',
			'tschutter',
			'tw2113',
			'tywayne',
			'tyxla',
			'uamv',
			'valendesigns',
			'veritaserum',
			'viper007bond',
			'voldemortensen',
			'volodymyrc',
			'vortfu',
			'webord',
			'welcher',
			'westonruter',
			'willstedt',
			'wonderboymusic',
			'wordpressorru',
			'yo-l1982',
		);
	}

	function external_libraries() {
		return array(
			array( 'Backbone.js', 'http://backbonejs.org/' ),
			array( 'Class POP3', 'http://squirrelmail.org/' ),
			array( 'Color Animations', 'http://plugins.jquery.com/color/' ),
			//		array( 'ColorPicker', 'http://' ),
			array( 'Horde Text Diff', 'http://pear.horde.org/' ),
			array( 'hoverIntent', 'http://plugins.jquery.com/project/hoverIntent' ),
			array( 'imgAreaSelect', 'http://odyniec.net/projects/imgareaselect/' ),
			array( 'Iris', 'https://github.com/Automattic/Iris' ),
			array( 'jQuery', 'http://jquery.com/' ),
			array( 'jQuery UI', 'http://jqueryui.com/' ),
			array( 'jQuery Hotkeys', 'https://github.com/tzuryby/jquery.hotkeys' ),
			array( 'jQuery serializeObject', 'http://benalman.com/projects/jquery-misc-plugins/' ),
			array( 'jQuery.query', 'http://plugins.jquery.com/query-object/' ),
			//		array( 'jquery.schedule', 'http://' ),
			array( 'jQuery.suggest', 'http://plugins.jquery.com/project/suggest' ),
			array( 'jQuery UI Touch Punch', 'http://touchpunch.furf.com/' ),
			array( 'json2', 'https://github.com/douglascrockford/JSON-js' ),
			array( 'Masonry', 'http://masonry.desandro.com/' ),
			array( 'MediaElement.js', 'http://mediaelementjs.com/' ),
			array( 'PclZip', 'http://www.phpconcept.net/pclzip/' ),
			array( 'PemFTP', 'http://www.phpclasses.org/browse/package/1743.html' ),
			array( 'phpass', 'http://www.openwall.com/phpass/' ),
			array( 'PHPMailer', 'http://code.google.com/a/apache-extras.org/p/phpmailer/' ),
			array( 'Plupload', 'http://www.plupload.com/' ),
			array( 'SimplePie', 'http://simplepie.org/' ),
			array( 'The Incutio XML-RPC Library', 'https://code.google.com/archive/p/php-ixr/' ),
			array( 'Thickbox', 'http://codylindley.com/thickbox/' ),
			array( 'TinyMCE', 'http://www.tinymce.com/' ),
			array( 'Twemoji', 'https://github.com/twitter/twemoji' ),
			array( 'Underscore.js', 'http://underscorejs.org/' ),
			array( 'zxcvbn', 'https://github.com/dropbox/zxcvbn' ),
		);
	}
}
