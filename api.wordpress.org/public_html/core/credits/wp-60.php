<?php

class WP_60_Credits extends WP_Credits {

	public function groups() {
		$groups = [
			'core-developers'         => [
				'name'    => 'Noteworthy Contributors',
				'type'    => 'titles',
				'shuffle' => false,
				'data'    => [
					'matt'                => [ 'Matt Mullenweg', 'Release Lead' ],
					'priethor'            => [ 'Héctor Prieto', 'Release Lead' ],
					'annezazu'            => [ 'Anne McCarthy', 'Release Lead' ],
					'peterwilsoncc'       => [ 'Peter Wilson', 'Release Lead' ],
					'zieladam'            => 'Adam Zielinski',
					'gziolo'              => 'Greg Ziółkowski',
					'chaion07'            => 'Ahmed Chaion',
					'costdev'             => 'Colin Stewart',
					'ndiego'              => 'Nick Diego',
					'bph'                 => 'Birgit Pauli-Haack',
					'milana_cap'          => 'Milana Cap',
					'webcommsat'          => 'Abha Thakor',
					'dansoschin'          => 'Dan Soschin',
					'Boniu91'             => 'Piotrek Boniu',
					'ironprogrammer'      => 'Brian Alexander',
					'critterverse'        => 'Channing Ritter',
					'matveb'              => 'Matias Ventura',
					'SergeyBiryukov'      => 'Sergey Biryukov',
					'audrasjb'            => 'Jean-Baptiste Audras',
					'Mamaduka'            => 'George Mamadashvili',
					'ntsekouras'          => 'Nik Tsekouras',
					'oandregal'           => 'André',
					'hellofromTonya'      => 'Tonya Mork',
					'get_dave'            => 'Dave Smith',
					'poena'               => 'Carolina Nymark',
					'talldanwp'           => 'Daniel Richards',
					'youknowriad'         => 'Riad Benguella',
					'fcoveram'            => 'Francisco Vera',
				],
			],
			'contributing-developers' => [
				'name'    => false,
				'type'    => 'titles',
				'shuffle' => true,
				'data'    => [
					'kjellr'              => 'Kjell Reigstad',
					'femkreations'        => 'Femy Praseeth',
					'aristath'            => 'Ari Stathopoulos',
					'glendaviesnz'        => 'Glen Davies',
					'jorgefilipecosta'    => 'Jorge Costa',
					'Joen'                => 'Joen Asmussen',
					'spacedmonkey'        => 'Jonny Harris',
					'mciampini'           => 'Marco Ciampini',
					'jrf'                 => 'Juliette Reinders Folmer',
					'ramonopoly'          => 'Ramon James',
					'justinahinon'        => 'Justin Ahinon',
					'noisysocks'          => 'Robert Anderson',
					'johnbillion'         => 'John Blackbourn',
					'aaronrobertshaw'     => 'Aaron Robertshaw',
					'carlosgprim'         => 'Carlos Garcia',
					'sabernhardt'         => 'Stephen Bernhardt',
					'andrewserong'        => 'Andrew Serong',
					'davidbaumwald'       => 'David Baumwald',
					'scruffian'           => 'Ben Dwyer',
					'desrosj'             => 'Jonathan Desrosiers',
					'cbravobernal'        => 'Carlos Bravo',
					'JeffPaul'            => 'Jeff Paul',
					'marybaum'            => 'Mary Baum',
					'alexstine'           => 'Alex Stine',
				],
			],
		];

		return $groups;
	}

	public function props() {
		return [
			'_smartik_',
			'0mirka00',
			'5um17',
			'aadilali',
			'aandrewdixon',
			'aaronrobertshaw',
			'abdullahramzan',
			'adamsilverstein',
			'addiestavlo',
			'adi64bit',
			'afercia',
			'afragen',
			'agepcom',
			'ahsan03',
			'ajlende',
			'ajoah',
			'alanjacobmathew',
			'alansyue',
			'albertomake',
			'alefesouza',
			'alex897',
			'alexstine',
			'aliakseyenkaihar',
			'aljullu',
			'alkesh7',
			'alokstha1',
			'alshakero',
			'amustaque97',
			'andraganescu',
			'andrewserong',
			'ankit-k-gupta',
			'annezazu',
			'anoopranawat',
			'antonrinas',
			'antonvlasenko',
			'antonynz',
			'antpb',
			'arasae',
			'arcangelini',
			'aristath',
			'arnee',
			'arpitgshah',
			'artdecotech',
			'arthur791004',
			'asaquzzaman',
			'atachibana',
			'atomicjack',
			'audrasjb',
			'aurooba',
			'azaozz',
			'azhiyadev',
			'azouamauriac',
			'barryceelen',
			'barryhughes',
			'bartoszgadomski',
			'bedas',
			'bernhard-reiter',
			'bettyjj',
			'bhrugesh12',
			'binarymoon',
			'birgire',
			'blogaid',
			'boniu91',
			'bookdude13',
			'boonebgorges',
			'bph',
			'bronsonquick',
			'brookedot',
			'brookemk',
			'caraya',
			'carlosgprim',
			'cbigler',
			'cbravobernal',
			'cbringmann',
			'certainstrings',
			'chaion07',
			'chanthaboune',
			'charleyparkerdesign',
			'charlyox',
			'chesio',
			'chintan1896',
			'chouby',
			'chriscct7',
			'chrisvanpatten',
			'clonemykey',
			'clorith',
			'clubkert',
			'codente',
			'coffee2code',
			'computerguru',
			'conner_bw',
			'costasovo',
			'costdev',
			'courane01',
			'cr0ybot',
			'credo61',
			'critterverse',
			'csesumonpro',
			'cybr',
			'czapla',
			'danielbachhuber',
			'danieldudzic',
			'danieliser',
			'dansoschin',
			'darerodz',
			'davidbaumwald',
			'davidbinda',
			'dd32',
			'delowardev',
			'denishua',
			'dennisatyoast',
			'desrosj',
			'devutpol',
			'dgwyer',
			'dhanendran',
			'dharm1025',
			'dhusakovic',
			'dilipbheda',
			'dimadin',
			'dlh',
			'dmsnell',
			'dolphingg',
			'domainsupport',
			'donmhico',
			'dpcalhoun',
			'drago239',
			'drewapicture',
			'dromero20',
			'dshanske',
			'eddystile',
			'ehtis',
			'eidolonnight',
			'eliezerspp',
			'elifvish',
			'ellatrix',
			'eric3d',
			'espiat',
			'estelaris',
			'etaproducto',
			'everpress',
			'fabiankaegy',
			'faison',
			'fcoveram',
			'felipeelia',
			'fierevere',
			'figureone',
			'flixos90',
			'florianbrinkmann',
			'foliovision',
			'francina',
			'frankei',
			'fullofcaffeine',
			'furi3r',
			'gabertronic',
			'gadhiyaravi',
			'garrett-eclipse',
			'garyj',
			'genosseeinhorn',
			'georgestephanis',
			'geriux',
			'get_dave',
			'glendaviesnz',
			'goaroundagain',
			'grandeljay',
			'grantmkin',
			'grapplerulrich',
			'greglone',
			'gregoiresailland',
			'gumacahin',
			'gvgvgvijayan',
			'gwwar',
			'gziolo',
			'hareesh-pillai',
			'hasanuzzamanshamim',
			'hasnainashfaq',
			'hazdiego',
			'helen',
			'helgatheviking',
			'hellofromtonya',
			'henrywright',
			'hilayt24',
			'hitendra-chopda',
			'hlashbrooke',
			'hristok',
			'htdat',
			'hypest',
			'ianatkins',
			'ianbelanger',
			'iandunn',
			'ianmjones',
			'imokol',
			'inc2734',
			'iogui',
			'ironprogrammer',
			'isabel_brison',
			'ishitaka',
			'itsamoreh',
			'iulia-cazan',
			'ivanlutrov',
			'jadpm',
			'jakeparis',
			'jameskoster',
			'janh2',
			'jarretc',
			'javiarce',
			'jazbek',
			'jb510',
			'jblz',
			'jdy68',
			'jeffmatson',
			'jeffpaul',
			'jeherve',
			'jeremyfelt',
			'jeremyyip',
			'jffng',
			'jhned',
			'jhnstn',
			'jigar-bhanushali',
			'jiteshdhamaniya',
			'jnz31',
			'joedolson',
			'joemcgill',
			'joen',
			'johnbillion',
			'johnjamesjacoby',
			'johnregan3',
			'johnstonphilip',
			'johnwatkins0',
			'jonoaldersonwp',
			'jontyravi',
			'jorbin',
			'jorgecontreras',
			'jorgefilipecosta',
			'josearcos',
			'joshf',
			'jostnes',
			'joyously',
			'jprieton',
			'jrchamp',
			'jrf',
			'jrivett',
			'jsnajdr',
			'jsnjohnston',
			'juanlopez4691',
			'juanmaguitar',
			'junaidkbr',
			'junsuijin',
			'justinahinon',
			'justinbusa',
			'kafleg',
			'kajalgohel',
			'kapacity',
			'kapilpaul',
			'karmatosed',
			'karolinakulinska',
			'karpstrucking',
			'kasparsd',
			'kbat82',
			'kebbet',
			'kevin940726',
			'kharisblank',
			'kirtan95',
			'kjellr',
			'kmix39',
			'knilkantha',
			'knutsp',
			'konradyoast',
			'kpegoraro',
			'kprovance',
			'kraftbj',
			'kubiq',
			'la-geek',
			'laurlittle',
			'layotte',
			'legendusmaximus',
			'lenasterg',
			'linux4me2',
			'lkraav',
			'lopo',
			'louislaugesen',
			'lschuyler',
			'luisherranz',
			'lukecavanagh',
			'lumpysimon',
			'macbookandrew',
			'madeinua',
			'madtownlems',
			'maguijo',
			'mahype',
			'malinevskiy',
			'malthert',
			'mamaduka',
			'manfcarlo',
			'manooweb',
			'manzurahammed',
			'markjaquith',
			'marv2',
			'marybaum',
			'mashikag',
			'mat-lipe',
			'mattchowning',
			'mattwiebe',
			'matveb',
			'mauriac',
			'mauteri',
			'maxkellermann',
			'mburridge',
			'mciampini',
			'mcsf',
			'mehedi890',
			'meher',
			'mgol',
			'mhimon',
			'michelangelovandam',
			'mikachan',
			'miken32',
			'mikeschroder',
			'milana_cap',
			'mirkolofio',
			'miss_jwo',
			'mista-flo',
			'mitogh',
			'mjkhajeh',
			'mjstoney',
			'mkaz',
			'mkox',
			'mmaattiiaass',
			'mmdeveloper',
			'mohadeseghasemi',
			'mor10',
			'moushik',
			'muhammadfaizanhaidar',
			'mukesh27',
			'multidots1896',
			'nabil_kadimi',
			'nacin',
			'nagpai',
			'nalininonstopnewsuk',
			'nathannoom',
			'navigatrum',
			'nayana123',
			'ndiego',
			'nekojonez',
			'netweb',
			'nextend_ramona',
			'nhadsall',
			'nickciske',
			'nidhidhandhukiya',
			'nmschaller',
			'noahtallen',
			'noisysocks',
			'nomnom99',
			'ntsekouras',
			'oakesjosh',
			'oandregal',
			'obenland',
			'ocean90',
			'oguzkocer',
			'omaeyusuke',
			'onemaggie',
			'opr18',
			'opurockey',
			'otshelnik-fm',
			'overclokk',
			'ovidiul',
			'oztaser',
			'paaljoachim',
			'paapst',
			'pablohoneyhoney',
			'paragoninitiativeenterprises',
			'paranoia1906',
			'paulkevan',
			'pavanpatil1',
			'pbearne',
			'pbiron',
			'pbking',
			'pedromendonca',
			'pento',
			'petaryoast',
			'peterwilsoncc',
			'petitphp',
			'petrosparaskevopoulos',
			'pgpagely',
			'pikamander2',
			'pls78',
			'poena',
			'pooja1210',
			'pravinparmar2404',
			'presskopp',
			'presstoke',
			'priethor',
			'priyank9033',
			'pross',
			'pschrottky',
			'psmits1567',
			'pypwalters',
			'pyrobd',
			'r-a-y',
			'r0bsc0tt',
			'rachelbaker',
			'rafiahmedd',
			'rahe',
			'rahmohn',
			'ramonopoly',
			'rarst',
			'ravanh',
			'ravipatel',
			'razvanonofrei',
			'rehanali',
			'revgeorge',
			'rianrietveld',
			'ribaricplusplus',
			'richtabor',
			'richybkreckel',
			'ricomoorman',
			'rilwis',
			'rmccue',
			'rolfsiebers',
			'rsiddharth',
			'rufus87',
			'rumpel2116',
			'ryan',
			'ryelle',
			'ryokuhi',
			'sabbir1991',
			'sabbirshouvo',
			'sabernhardt',
			'sainthkh',
			'samiff',
			'samikeijonen',
			'santosguillamot',
			'sanzeeb3',
			'sarayourfriend',
			'sathyapulse',
			'satollo',
			'sausajez',
			'sayedulsayem',
			'sbossarte',
			'schlessera',
			'sclayf1',
			'scruffian',
			'sebastienserre',
			'sergeybiryukov',
			'shedonist',
			'sheepysheep60',
			'shimotomoki',
			'shireling',
			'shital-patel',
			'shreyasikhar26',
			'sierratr',
			'silb3r',
			'simonhammes',
			'siobhyb',
			'sivel',
			'skorasaurus',
			'smit08',
			'snapfractalpop',
			'socalchristina',
			'soean',
			'spacedmonkey',
			'spencercameron',
			'stacimc',
			'stefanfisk',
			'stephenharris',
			'stevegrunwell',
			'subrataemfluence',
			'sumitsingh',
			'sunil25393',
			'sunyatasattva',
			'supercleanse',
			'superpoincare',
			'swb1192',
			'swissspidy',
			'synchro',
			'tabrisrp',
			'talldanwp',
			'tharsheblows',
			'thelovekesh',
			'themattroyal',
			'thimalw',
			'thomasplevy',
			'tillkruess',
			'timothyblynjacobs',
			'tnolte',
			'tobifjellner',
			'tomalec',
			'tomasztunik',
			'tomjdevisser',
			'toro_unit',
			'trex005',
			'tszming',
			'ttahmouch',
			'tumas2',
			'twistermc',
			'twstokes',
			'tyxla',
			'tzipporahwitty',
			'uday17035',
			'ugljanin',
			'ugyensupport',
			'upsuper',
			'utkarshpatel',
			'utz119',
			'uzumymw',
			'valer1e',
			'vcanales',
			'versusbassz',
			'viper007bond',
			'vladolaru',
			'voldemortensen',
			'volodymyrkolesnykov',
			'vortfu',
			'w33zy',
			'walbo',
			'waterfire',
			'webcommsat',
			'webmandesign',
			'webtechpooja',
			'welcher',
			'wendyjchen',
			'west7',
			'westi',
			'westonruter',
			'whoisnegrello',
			'whyisjake',
			'wido',
			'wildworks',
			'wonderboymusic',
			'wpe_bdurette',
			'wpmakenorg',
			'wpsoul',
			'wraithkenny',
			'wslyhbb',
			'xiven',
			'xknown',
			'youknowriad',
			'zaguiini',
			'zebulan',
			'zieladam',
			'znuff',
			'zodiac1978',
		];
	}

	public function external_libraries() {
		return [
			[ 'Babel Polyfill', 'https://babeljs.io/docs/en/babel-polyfill' ],
			[ 'Backbone.js', 'http://backbonejs.org/' ],
			[ 'Class POP3', 'https://squirrelmail.org/' ],
			[ 'clipboard.js', 'https://clipboardjs.com/' ],
			[ 'Closest', 'https://github.com/jonathantneal/closest' ],
			[ 'CodeMirror', 'https://codemirror.net/' ],
			[ 'Color Animations', 'https://plugins.jquery.com/color/' ],
			[ 'getID3()', 'http://getid3.sourceforge.net/' ],
			[ 'FormData', 'https://github.com/jimmywarting/FormData' ],
			[ 'Horde Text Diff', 'https://pear.horde.org/' ],
			[ 'hoverIntent', 'http://cherne.net/brian/resources/jquery.hoverIntent.html' ],
			[ 'imgAreaSelect', 'http://odyniec.net/projects/imgareaselect/' ],
			[ 'Iris', 'https://github.com/Automattic/Iris' ],
			[ 'jQuery', 'https://jquery.com/' ],
			[ 'jQuery UI', 'https://jqueryui.com/' ],
			[ 'jQuery Hotkeys', 'https://github.com/tzuryby/jquery.hotkeys' ],
			[ 'jQuery serializeObject', 'http://benalman.com/projects/jquery-misc-plugins/' ],
			[ 'jQuery.query', 'https://plugins.jquery.com/query-object/' ],
			[ 'jQuery.suggest', 'https://github.com/pvulgaris/jquery.suggest' ],
			[ 'jQuery UI Touch Punch', 'http://touchpunch.furf.com/' ],
			[ 'json2', 'https://github.com/douglascrockford/JSON-js' ],
			[ 'Lodash', 'https://lodash.com/' ],
			[ 'Masonry', 'http://masonry.desandro.com/' ],
			[ 'MediaElement.js', 'http://mediaelementjs.com/' ],
			[ 'Moment', 'http://momentjs.com/' ],
			[ 'PclZip', 'http://www.phpconcept.net/pclzip/' ],
			[ 'PemFTP', 'https://www.phpclasses.org/package/1743-PHP-FTP-client-in-pure-PHP.html' ],
			[ 'phpass', 'http://www.openwall.com/phpass/' ],
			[ 'PHPMailer', 'https://github.com/PHPMailer/PHPMailer' ],
			[ 'Plupload', 'http://www.plupload.com/' ],
			[ 'random_compat', 'https://github.com/paragonie/random_compat' ],
			[ 'React', 'https://reactjs.org/' ],
			[ 'Redux', 'https://redux.js.org/' ],
			[ 'Requests', 'http://requests.ryanmccue.info/' ],
			[ 'SimplePie', 'http://simplepie.org/' ],
			[ 'The Incutio XML-RPC Library', 'https://code.google.com/archive/p/php-ixr/' ],
			[ 'Thickbox', 'http://codylindley.com/thickbox/' ],
			[ 'TinyMCE', 'https://www.tinymce.com/' ],
			[ 'Twemoji', 'https://github.com/twitter/twemoji' ],
			[ 'Underscore.js', 'http://underscorejs.org/' ],
			[ 'whatwg-fetch', 'https://github.com/github/fetch' ],
			[ 'zxcvbn', 'https://github.com/dropbox/zxcvbn' ],
		];
	}
}

