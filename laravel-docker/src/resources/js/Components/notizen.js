
const app = Vue.createApp({
	data() {
		return {
			notizen: [],
			notiz: {notizID: 0, titel:'', inhalt: '', erstellungsdatum:'', wichtig:false},
			errors: [],
			showform : false,
			showerr : false,
			page: 1,
			maxpage: -1,
			notizCount: 0,
		}
	},

	methods: {
		nextPage: function() {		// get the next page of addresses
			this.page++;
			this.getNotizCount();
			this.getNotizen();
		},
		previousPage: function() {	// get the previous page of addresses
			this.page--;
			this.getNotizCount();
			this.getNotizen();
		},
		firstPage: function() {		// jump back to the first page of addresses
			this.page=1;
			this.getNotizCount();
			this.getNotizen();
		},
		lastPage: function() {		// jump to the last page of addresses
			this.getNotizCount();
			this.page = this.maxpage;
			this.getNotizen();
		},
		getNotizCount: function() {	// get the total number of addresses in the addressbook and calculate pages
			axios.get("http://localhost:8080/api/notiz/count")
			.then(response => {
				this.notizCount = parseInt(response.data.cnt);
				this.maxpage = Math.floor(this.notizCount / 12) + ((this.notizCount % 12 > 0) ? 1 : 0);							
			})
		},
		getNotizen: function() {		// get the current page
			axios.get("http://localhost:8080/api/notiz/pg/"+this.page)
			.then(response => {
				this.notizen = response.data;
				console.log(this.notizen);
			})
		},
		editNotiz: function(id) {		// edit an entry
			axios.get("http://localhost:8080/api/notiz/" + parseInt(id))
			.then(response => {
				this.notizen = response.data[0];
				this.showform = true;
			})
		},
		submitNotiz: function(notiz) {	// write an updated notiz
			axios.put("http://localhost:8080/api/notiz/" + parseInt(notiz.notizID),notiz)
			.then(response => {
				console.log(response);
				if( !(typeof response.data.errors === 'undefined')) {
					errors = response.data.errors;
					if(errors.length !=0) {
						this.showerr = true;
						this.errors = errors;
						return;
					}
				} else {
					// erase the notiz form once finished updating
					this.notiz = {notizID: 0, titel:'', inhalt: '', erstellungsdatum:'', wichtig:false};
					this.errors = [];	// erase all errors from the submission area
					alert("Erfolgreich");	// alert user, pause
					this.showform = false;	//erase the form
					this.getNotiz();	// update the page
					this.getNotizCount();
				}
			})
		},
		postNotiz: function(notiz) {		// write a new address into the addressbook
			axios.post("http://localhost:8080/api/notiz/post",notiz)
			.then(response => {
				console.log(response);
				if( !(typeof response.data.errors === 'undefined')) {
					errors = response.data.errors;
					if(errors.length != 0) {
						this.showerr = true;
						this.errors = errors;
						return;
					}
				} else {
					this.notiz = {notizID: 0, titel:'', inhalt: '', erstellungsdatum:'', wichtig:false};
					this.errors = [];
					alert("Efolgreich");
					this.showform = false;
					this.getNotiz();	// update the page
					this.getNotizCount();
				}
			})
		},
		deleteAddress: function(notiz) {		// delete a reocrd
			if(confirm("Sind Sie sicher??")){		
				axios.delete("http://localhost:8080/api/notiz/"+parseInt(notiz))
				.then(response => {
					this.getNotiz();	// update the page
					this.getNotizCount();
				});
			} else {
				alert("Delete aborted");
			}
		}
	},
	created() {		// on startup, get the first page of notes after getting the size of the db and calculating the pages
		this.getNotizCount();
		this.getNotizen();
	}
});
app.mount('#app')
