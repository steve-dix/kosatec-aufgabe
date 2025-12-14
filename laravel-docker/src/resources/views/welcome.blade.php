<!DOCTYPE html>
<html>
<head>
    <title>Notizen</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
</head>
<body>
<h2>Notizen</h2>

@verbatim
<div id="app">
    <!-- Table component -->
    <notizen
		:notizen="notizen"
		:page="page"
		:maxpage="maxpage"
		:disabled="disabled"
		@edit="editNotiz"
		@delete="deleteNotiz"
		@first-page="firstPage"
		@previous-page="previousPage"
		@next-page="nextPage"
		@last-page="lastPage"
		@show-form="showform = $event"
    ></notizen>

    <!-- Form -->
    <div v-if="showform" id="form">
        <div id="formblock">
            <fieldset v-if="errors.length">
                <legend>Errors</legend>
                <div v-for="(err,index) in errors" :key="index">{{ err.msg }}</div>
            </fieldset>

            <fieldset>
                <legend>Notiz</legend>
                <form @submit.prevent="submitForm">
                    <input type="hidden" v-model="notiz.notizID">
                    <label>Titel</label><br>
                    <input type="text" v-model="notiz.titel" required><br>
                    <label>Inhalt</label><br>
                    <textarea v-model="notiz.inhalt" rows="8" cols="30" required></textarea><br>
                    <label>Erstellungsdatum</label><br>
                    <input type="text" v-model="notiz.erstellungsdatum"><br>
                    <label>Wichtig</label>
                    <select v-model="notiz.wichtig">
                        <option value="N">N</option>
                        <option value="Y">Y</option>
                    </select><br><br>
                    <button type="submit">{{ notiz.notizID ? 'Update' : 'Create' }}</button>
                    <button type="button" @click="cancelForm">Cancel</button>
                </form>
            </fieldset>
        </div>
    </div>
</div>
@endverbatim

<script>
const Notizen = {
    props: ['notizen','page','maxpage','disabled'],
    emits: ['edit','delete','firstPage','previousPage','nextPage','lastPage','showForm'],
    template: `
    <div>
        <button @click="$emit('show-form', true)">New Entry</button>
        <table>
            <thead>
                <tr>
                    <th>Titel</th>
                    <th>Inhalt</th>
                    <th>Erstellungsdatum</th>
                    <th>Wichtig</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="notiz in notizen" :key="notiz.id">
                    <td>@{{ notiz.titel }}</td>
                    <td>@{{ notiz.inhalt }}</td>
                    <td>@{{ notiz.erstellungsdatum }}</td>
                    <td>@{{ notiz.wichtig }}</td>
                    <td>
                        <button @click="$emit('edit', notiz.id)">Edit</button>
                        <button @click="$emit('delete', notiz.id)">Del</button>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5">
                        <button :disabled="page<2" @click="$emit('firstPage')">⇧</button>
                        <button :disabled="page<2" @click="$emit('previousPage')">⇦</button>
                        <button :disabled="page==maxpage" @click="$emit('nextPage')">⇨</button>
                        <button :disabled="page==maxpage" @click="$emit('lastPage')">⇩</button>
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>
    `
};

const app = Vue.createApp({
    data() {
        return {
            notizen: [],
            notiz: {notizID:0, titel:'', inhalt:'', erstellungsdatum:'', wichtig:'N'},
            errors: [],
            showform: false,
            page: 1,
            maxpage: 1,
            disabled: false
        };
    },
    components: {Notizen},
    methods: {
        refresh()
		{
            this.getNotizCount();
            this.getNotizen();
        },
		
        getNotizen()
		{
            axios.get('/api/notiz/pg/'+parseInt(this.page))
                .then(response => { 
						console.log(response);
						this.notizen = response.data; 
					}
				)
                .catch(err => console.error(err));
        },
		
        getNotizCount()
		{
            axios.get('/api/notiz/count')
                .then(resp => { 
                    const count = parseInt(resp.data); 
                    this.maxpage = Math.ceil(count/12);
                })
                .catch(err => console.error(err));
        },
		
        firstPage()
		{ 
			this.page=1; 
			this.refresh(); 
		},
		
        previousPage()
		{ 
			if(this.page>1) {
				this.page--;
			}
			this.refresh(); 
		},
        
		nextPage()
		{ 
			if(this.page<this.maxpage) { 
				this.page++; 
			}
			this.refresh(); 
		},
        
		lastPage()
		{ 
			this.page=this.maxpage; 
			this.refresh(); 
		},

        editNotiz(id)
		{
            axios.get(`/api/notiz/${id}`)
                .then(resp => {
                    if(resp.data) {
                        this.notiz = resp.data; 
                        this.showform = true;
                    } else {
                        alert('Notiz not found');
                    }
                })
                .catch(err => console.error(err));
        },

        deleteNotiz(id)
		{
            if(confirm('Sind Sie sicher?')){
                axios.delete(`/api/notiz/${id}`)
                    .then(() => this.refresh())
                    .catch(err => console.error(err));
            }
        },

        submitForm()
		{
			let request = 0;
			console.log(this.notiz);
			if (this.notiz.notizID === 0) {
				// Create a new note
				const post = { 
						titel: this.notiz.titel, 
						inhalt: this.notiz.inhalt, 
						erstellungsdatum: this.notiz.erstellungsdatum, 
						wichtig: this.notiz.wichtig
					}
				console.log(post);
				request = axios.post('/api/notiz/post',post);
			} else {
				// Update existing note
				request = axios.put(`/api/notiz/`+parseInt(this.notiz.notizID), this.notiz);
			}

            request.then(() => { 
                this.showform = false; 
                this.resetForm(); 
                this.refresh();
            }).catch(err => {
                if(err.response && err.response.data.errors){
                    this.errors = err.response.data.errors;
                } else {
                    console.error(err);
                }
            });
        },

        cancelForm()
		{
            this.showform = false;
            this.resetForm();
        },

        resetForm()
		{
            this.notiz = {notizID:0, titel:'', inhalt:'', erstellungsdatum:'', wichtig:'N'};
            this.errors = [];
        }
    },
    created()
	{
        this.refresh();
    }
});

app.mount('#app');
</script>
</body>
</html>

