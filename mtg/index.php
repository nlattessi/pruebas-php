<?php
$json = file_get_contents('JOU.json');
$data = json_decode($json,true);

//print_r($data);

echo $data['name'];
echo '<br/><br/>';

foreach($data['cards'] as $card) {
	echo $card['name'], '<br/>';
	echo $card['manaCost'], '<br/>';
	echo $card['type'], '<br/>';
	echo $card['rarity'], '<br/>';
	echo $card['text'], '<br/>';
	if (isset($card['power'])) {
		echo $card['power'] . '/' . $card['toughness'], '<br/>';
	}
	echo '<br/>';
}
