function filter(){
	var x = document.getElementById("filter").value;
	console.log(x);
	fetch('livesearch.php?name=' + x)
		.then(function(result){
			console.log(result);
			return result.json(); //promise for parsed json
		})
		.then(function(ajax_data) {
			console.log(ajax_data['name']);
			if (Object.entries(ajax_data['success'])){
				display(ajax_data['name']);
			}
		});
}

function display(names) {
	var text = "";
	
	document.getElementById('result').innerHTML = "<h2></h2>";
	
	
	for (let i=0; i<names.length; i++){
		text += "<h2><p><a href='viewchamp.php?id=" + names[i].id + "'>" + names[i].name + "</a> <small><a href='editchamp.php?id=" + names[i].id + "'>edit</a></small></p></h2>";
	}
	
	document.getElementById('result').innerHTML += text;
}

function category(selected){
	fetch('category.php?cate=' + selected)
		.then(function(result){
			console.log(result);
			return result.json();
		})
		.then(function(category){
			console.log(category['cate']);
			if (Object.entries(category['success'])){
				display(category['cate']);
			}
		});
}