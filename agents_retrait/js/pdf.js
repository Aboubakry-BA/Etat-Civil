window.onload = function(){
	document.getElementById("button").addEventListener("click", ()=>{
		const extrait = this.document.getElementById("extrait");
		console.log(extrait);
		console.log(window);
		var opt = {
			margin: 0.2,
			filename: 'extrait.pdf',
			image: {type:'jpeg', quality: 1},
			html2canavas: {scale: 1},
			jsPDF: {unit: 'in', format: 'letter', orientation: 'portrait'}
		};
		html2pdf().from(extrait).set(opt).save();
	})
}