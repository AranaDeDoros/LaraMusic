<template>
  <div>
    <div class="mt-3">
    <form @submit.prevent="updateRecord(record)" v-if="editarAlbum">
      <h3>Edit album</h3>
      <input type="text" placeholder="Artist" class="form-control mb-2" v-model="record.artist" />
      <input type="text" placeholder="Album"  class="form-control mb-2" v-model="record.album"/>
      <input type="text" placeholder="Year" class="form-control mb-2" v-model="record.year"/>
      <input type="text" placeholder="Country" class="form-control mb-2" v-model="record.country"/>
      <input type="text" placeholder="# of tracks" class="form-control mb-2" v-model="record.ntracks" />
      <input type="text" placeholder="album length" class="form-control mb-2" v-model="record.length" />
      <div class="custom-control custom-checkbox mb-2">
		<input id="statusE" name="statusE" autocomplete="off" 
		class="custom-control-input" value="accepted" type="checkbox"
		:checked="wasListened"
		>
		<label for="statusE" class="custom-control-label">
	      Status
	    </label>
	  </div>

        <button type="submit" class="btn btn-success">Save</button>	
       <button class="btn btn-danger" @click="cancelEdition()">Cancel</button>
    </form>


       <form @submit.prevent="addRecord" v-else>
      <h3>Add album</h3>
      <input type="text" placeholder="Artist" class="form-control mb-2" v-model="record.artist" />
      <input type="text" placeholder="Album"  class="form-control mb-2" v-model="record.album"/>
      <input type="text" placeholder="Year" class="form-control mb-2" v-model="record.year"/>
      <input type="text" placeholder="Country" class="form-control mb-2" v-model="record.country"/>
      <input type="text" placeholder="# of tracks" class="form-control mb-2" v-model="record.ntracks" />
      <input type="text" placeholder="album length" class="form-control mb-2" v-model="record.length" />
    
      <div class="custom-control custom-checkbox mb-2">
		<input id="status" name="status" autocomplete="off" 
		class="custom-control-input" value="accepted" type="checkbox">
		<label for="status" class="custom-control-label">
	      Status
	    </label>
	</div>
   <button type="submit" class="btn btn-primary">Agregar</button>
      </form>
</div>
 

    <h3>Listing</h3>

      <table class="table">
  <thead>
    <tr>
      <th scope="col">Album</th>
      <th scope="col">Artist</th>
      <th scope="col">Year</th>
      <th scope="col">Status</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
    <tr v-for="(item, index) in albums" :key="index">
      <td>{{item.ALBUM}}</td>
      <td>{{item.ARTISTA}}</td>
      <td>{{item.ANIO}}</td>
      <td v-if="item.STATUS > 0">
      	<span class="badge badge-success"><i class="fas fa-check-check"></i>Listened</span>
      </td>
      <td v-else>
      	<span class="badge badge-danger"><i class="fas fa-check-minus-circle"></i>Pending</span>	
      </td>
      <td>
      <button class="btn btn-sm btn-warning" 
    		@click="editRecord(item)">
    		Edit
    </button>
    <button class="btn btn-sm btn-danger" 
    		@click="deleteRecord(item, index)">
    		Delete
    </button>
	</td>
    </tr>
    
    
  </tbody> 	
 </table>
    </div>
   
  </div>
</template>

<script>
export default {
  data() {
  	return {
  		albums: [],
  		record: {
  			artist:'',
  			album:'',
  			year:'',
  			country:'',
  			ntracks:'',
  			length:'',
  			status:'',
  			id_artist:'',
  			id_album:'',
  			id_pivot:''
  			},
  		params:{},
  		editarAlbum:false,
  		wasListened:false,
  	}
   
  },
  created() {
  	this.getAlbums();
  
  },
  methods: {
  		getAlbums(){
  				 axios.get("/albums").then(res => {
  	 	this.albums = res.data;
      console.log(res.data);
    });
  		},
  		cancelEdition(){
  			this.editarAlbum = false;
  			this.record = {};
  		},
  		showEditionForm(){
  			this.editarAlbum = !this.editarAlbum;
  		},
  		addRecord(){
  			if(this.record.artist === '' ||
  				this.record.album === '')
  			{
  				alert('Fill the artist and album');
  			}else{


  			const params = {
  				artist : this.record.artist,
  				album : this.record.album,
  				year : this.record.year,
  				country : this.record.country,
  				ntracks : this.record.ntracks,
  				length : this.record.length,
  				status : document.getElementById('status').checked ? 1 : 0
  			};

  			this.record.artist = '';
  			this.record.album = '';
  			this.record.year = '';
  			this.record.country = '';
  			this.record.ntracks = '';
  			this.record.length = '';
  			this.record.status = ''

  			console.log(params);

  			 axios.post("/albums", params).then(res => {
  			 	if(res.data.data){
        			this.albums.push(res.data.data);
        			console.log(res.data.data);
        		}else{
        			alert('error');
        		}
      		});
  			}




  		},
  		editRecord(item){
  			this.editarAlbum = true;
  			//create params object
  			this.record.artist = item.ARTISTA;
  			this.record.album = item.ALBUM;
  			this.record.year = item.ANIO;
  			this.record.country = item.COUNTRY;
  			this.record.ntracks = item.NTRACKS;
  			this.record.length = item.LENGTH;
  			this.record.status = item.STATUS;
  			this.record.id_artist = item.IDARTISTA;
  			this.record.id_album = item.IDALBUM;
  			this.record.id_pivot = item.IDPIVOTE;


  			if(this.record.status === 1){
  				this.wasListened = true;
  			}else{
  				this.wasListened = false;
  			}


  		},
  		updateRecord(record){
  			//axios
  			const params = {
  				artist: this.record.artist,
  				album:this.record.album,
  				year:this.record.year,
  				country:this.record.country,
  				ntracks: this.record.ntracks,
  				length: this.record.length,
  				status:this.record.status,
  				id_artist:this.record.id_artist,
  				id_album:this.record.id_album,
  				id_pivot:this.record.id_pivot,
  			} 

  			axios.put(`/albums/${params.id_pivot}`,params).then(res=>{
  				console.log(res.data.data[0]);

  				const idx = this.albums.findIndex(albumBuscar=>{
  					albumBuscar.id === res.data.data[0].id;
  				});
  				 this.albums[idx] = res.data.data[0]; //base de datos
		 	});
		 	this.record = {};
  			this.editarAlbum = false;
  			 this.getAlbums();
  		},
  		deleteRecord(record, index){
  			const idA = record.IDARTISTA;
  			const idAl = record.IDALBUM;
  			const idP = record.IDPIVOTE;
  			const ids = idA+'&'+idAl+'&'+idP;


  			axios.delete(`/albums/${ids}`).then(() => {
        this.albums.splice(index, 1);
        console.log(this.albums);
      		});

  		}
    }
  
};
</script>
