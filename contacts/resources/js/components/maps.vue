<template html>
    <div id="mapa" >
         <div id="map" ref="geral" class="tw-h-full"></div>
    </div>
</template>
<script>
export default {
    props:{
        contacts:[],
        iconPath:String,
        posicaoInicial:{
            default: function() {
                return {
                    lat: -25.44368609755479, 
                    lng: -49.27899281918981
                };
            },
            type:Object
        },        
        zoom: {
            default:12,
            type: Number
        },
    },
    data: function() {
        return {
            map:'',
            localidade:{latitude:'',longitude:''},
            endCompleto: '',
            info: null,
            infoOpened:null,
            markers:[]
        }
    },
    mounted: function() {
        this.initMap();
        this.info = new google.maps.InfoWindow({content:''})
    },
    methods: {
        initMap: function() {
            // cria o mapa
            this.map = new google.maps.Map(this.$refs.geral, {
                zoom: this.zoom,
                center: this.posicaoInicial,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });

            // cria o marcador padrão
            const marker = new google.maps.Marker({
                position: this.posicaoInicial || {},
                map: this.map,
                draggable:false,
                animation: google.maps.Animation.DROP,
                icon: this.iconPath
            });

            let info = new google.maps.InfoWindow({
                content: '<b>R. Pasteur, 463 </br>Batel </br>Curitiba - PR </br>80250-104</b>',
                disableAutoPan: false
            });

             marker.addListener("click", function(e){ 
                if(this.infoOpened){
                    this.infoOpened.close();
                }
                this.infoOpened = info;
                info.open(this.map, marker);
            }.bind(this));
           
            $('#mapa').show();


        },
        openInfo(id){
            let marker = this.markers.find(function(c) {
                            return c.id == id;
                        });
            google.maps.event.trigger( marker.marker, 'click' );
            let map = marker.marker.get("map");
            map.setCenter(marker.marker.position);
            map.setZoom(this.zoom);
            $(marker.marker).trigger('click');
        },
        addMarkerContacts(){
            this.markers.map((m)=>{m.marker.setMap(null) && m.marker && m});
            this.markers = [];
            this.contacts.map(function(contact){
                const marker = this.createMarker({lat:parseFloat(contact.latitude),lng:parseFloat(contact.longitude)});
                let info = new google.maps.InfoWindow({
                    content: this.formatInfoWindow(contact),
                    disableAutoPan: false
                });
                marker.addListener("click", function(e){ 
                   if(this.infoOpened){
                        if(this.infoOpened.position.lat != marker.position.lat &&
                            this.infoOpened.position.lng != marker.position.lng
                        ){
                            this.infoOpened.close();
                            info.open(marker.get("map"), marker);
                            this.infoOpened = info;
                        }else{
                            this.infoOpened.close();
                            this.infoOpened = null;
                        }
                    }else{
                        info.open(marker.get("map"), marker);
                        this.infoOpened = info;
                    }
                     
                }.bind(this));
                
                this.markers.push({id:contact.id,marker:marker});
            }.bind(this));
        },
        formatInfoWindow(addres){
            let name = '<b>'+addres.nome+'</b></br>';
            let formatAddres = '<b>' + addres.logradouro;
            formatAddres += ', '+ addres.numero ;
            formatAddres += addres.complemento ? ' '+ addres.complemento + '</br>' : '</br>';
            formatAddres += addres.bairro + '</br>';
            formatAddres += addres.localidade;
            formatAddres += ' - ' + addres.uf + '</br>';
            formatAddres += addres.cep + '</b>';
            return name + formatAddres;
        },
        createMarker: function(posicao) {
            return new google.maps.Marker({
                position: posicao || this.inicio || {},
                map: this.map,
            });
        },
    }
}
</script>


