<html>
<head>
	<title>Forum jQuery</title>
</head>
<body>
<table id="matable">
	<tbody>
		<tr>
			<td>a</td>
			<td>r</td>
		</tr>
		<tr>
			<td>b</td>
			<td>i</td>
		</tr>
		<tr>
			<td>c</td>
			<td>v</td>
		</tr>
		<tr>
			<td>d</td>
			<td>b</td>
		</tr>
		<tr>
			<td>e</td>
			<td>o</td>
		</tr>
		<tr>
			<td>f</td>
			<td>z</td>
		</tr>
			<tr>
			<td>1</td>
			<td>23</td>
		</tr>
			<tr>
			<td>10</td>
			<td>24</td>
		</tr>
			<tr>
			<td>2</td>
			<td>230</td>
		</tr>
	</tbody>
</table>
 
<div>
	<p>Alphabétique<br/>colonne 1</p>
	<input type="button" onclick="sortTable('matable',0, 'desc')" value="tri desc col 1" />
	<input type="button" onclick="sortTable('matable',0, 'asc')" value="tri asc col1" /><br/>
 
	<input type="button" onclick="sortTable('matable',0, 'ndesc')" value="tri num desc col 1" />
	<input type="button" onclick="sortTable('matable',0, 'nasc')" value="tri num asc col1" /><br/>
 
	<input type="button" onclick="sortTable('matable',0, '?desc')" value="tri alphanum desc col 1" />
	<input type="button" onclick="sortTable('matable',0, '?asc')" value="tri alphanum asc col1" />
 
	<p>colonne 2</p>
	<input type="button" onclick="sortTable('matable',1, 'desc')" value="tri desc col2" />
	<input type="button" onclick="sortTable('matable',1, 'asc')" value="tri asc col2" /><br/>
 
	<input type="button" onclick="sortTable('matable',1, 'ndesc')" value="tri num desc col2" />
	<input type="button" onclick="sortTable('matable',1, 'nasc')" value="tri num asc col2" /><br/>
 
	<input type="button" onclick="sortTable('matable',1, '?desc')" value="tri alphanum desc col2" />
	<input type="button" onclick="sortTable('matable',1, '?asc')" value="tri alphanum asc col2" />
</div>
 
	</div>
	<script charset="utf-8" src="http://code.jquery.com/jquery-1.4.4.min.js"></script>
	<script>
 
function sortTable(tid, col, ord){
	var mybody = $("#"+tid).children('tbody'),
		lines = mybody.children('tr'),
		sorter = [],
		i = -1,
		j = -1;
 
	while(lines[++i]){
		sorter.push([lines.eq(i), lines.eq(i).children('td').eq(col).text()])
	}
 
	if (ord == 'asc') {
		sorter.sort();
	} else if (ord == 'desc') {
		sorter.sort().reverse();
	} else if (ord == 'nasc'){
		sorter.sort(function(a, b){return(a[1] - b[1]);});
	} else if (ord == 'ndesc'){
		sorter.sort(function(a, b){return(b[1] - a[1]);});
	} else if (ord == '?asc'){
		sorter.sort(function(a, b){
			var x = parseInt(a[1], 10),
				y = parseInt(b[1], 10);
 
			if (isNaN(x) || isNaN(y)){
				if (a[1] > b[1]){
					return 1;
				} else if(a[1] < b[1]){
					return -1;
				} else {
					return 0;
				}
			} else {
				return(a[1] - b[1]);
			}
		});
	} else if (ord == '?desc'){
		sorter.sort(function(a, b){
			var x = parseInt(a[1], 10),
				y = parseInt(b[1], 10);
 
			if (isNaN(x) || isNaN(y)){
				if (a[1] > b[1]){
					return -1;
				} else if(a[1] < b[1]){
					return 1;
				} else {
					return 0;
				}
			} else {
				return(b[1] - a[1]);
			}
		});
	}
 
	while(sorter[++j]){
		mybody.append(sorter[j][0]);
	}
}
 
		$(function(){
		});
 	</script>
</body>  
</html>