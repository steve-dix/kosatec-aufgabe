<template>
	<h2 id="title">Addressbook</h2>
	<div id="app">
		<div id="list">
			<button @click="showform=2">New Entry</button>
			<table>
				<thead>
					<tr><th>Name</th><th>Address</th><th>Phone</th><th>Email</th><th>Page {{page}} of {{maxpage}}</th></tr>
				</thead>
				<tbody>
					<tr v-for="addr in addresses">
						<td>{{ addr.name }}</td>
						<td v-html="addr.address"></td>
						<td>{{ addr.phone }}</td>
						<td>{{ addr.email }}</td>
						<td>
							<button @click="editAddress(addr.addressID)">Edit</button>&nbsp;
							<button @click="deleteAddress(addr.addressID)">Del</button>
						</td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<th colspan="5">
							<button class="paging" :disabled="page<2" :class="{'disable-input': disabled}" @click="firstPage()">⇧</button>
							<button class="paging" :disabled="page<2" :class="{'disable-input': disabled}" @click="previousPage()">⇦</button>
							<button class="paging" :disabled="page == maxpage" :class="{'disable-input': disabled}" @click="nextPage()">⇨</button>
							<button class="paging" :disabled="page == maxpage" :class="{'disable-input': disabled}" @click="lastPage()">⇩</button>
						</th>
					</tr>								
				</tfoot>
			</table>
			
		</div>
		<div id="form" v-if="showform">
			<div id="formblock">
				<fieldset id="errors" v-if="errors.length">
					<legend>Errors</legend>
					<span v-for="err in errors">
						{{err.msg}}<br/>
					</span>
				</fieldset>
				<fieldset id="formstyle">
					<legend>Address</legend>
					<form v-on:submit.prevent="if(showform==1) submitAddress(address); else postAddress(address);">
						<input type="hidden" name="addressID" v-model="address.addressID"/>
						<label>Name</label><br/>
						<input type="text" v-model="address.name" placeholder="Your Name"/><br/>
						<label>Address</label><br/>
						<textarea v-model="address.address" rows=8 cols=30></textarea><br/>
						<label>Phone</label><br/>
						<input type="text" v-model="address.phone" placeholder="Your phone number"/><br/>
						<label>Email</label><br/>
						<input type="text" v-model="address.email" placeholder="Your email" v-bind:class="emailClass" /><br/>
						<button type="submit">Submit</button><button type="cancel" @click="showform=false">Cancel</button>
					</form>
				</fieldset>
			</div>
		</div>
	</div>
</template>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>

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
			axios.get("http://localhost:8080/notiz/count")
			.then(response => {
				this.notizCount = parseInt(response.data.cnt);
				this.maxpage = Math.floor(this.notizCount / 12) + ((this.notizCount % 12 > 0) ? 1 : 0);							
			})
		},
		getNotizen: function() {		// get the current page
			axios.get("http://localhost:8080/notiz/pg/"+this.page)
			.then(response => {
				this.notizen = response.data;
			})
		},
		editNotiz: function(id) {		// edit an entry
			axios.get("http://localhost:8080/notiz/" + parseInt(id))
			.then(response => {
				this.address = response.data[0];
				this.showform = true;
			})
		},
		submitNotiz: function(notiz) {	// write an updated notiz
			axios.put("http://localhost:8080/notiz/" + parseInt(notiz.notizID),notiz)
			.then(response => {
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
			axios.post("http://localhost:8080/notiz/",notiz)
			.then(response => {
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
				axios.delete("http://localhost:8080/notiz/"+parseInt(notiz))
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
</script>
