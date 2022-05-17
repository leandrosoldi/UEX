@extends('layout')
@section('content')
<contact-component inline-template api="{{ env('API_HOST') }}"  > 
    <div class="tw-bg-gray-400 tw-grid tw-grid-cols-12 tw-grid-rows-2 sm:tw-grid-rows-1">
        <div class="sm:tw-col-span-4 sm:tw-h-screen sm:tw-m-0 tw-bg-gray-200 tw-col-span-12 tw-flex tw-flex-col tw-h-80 tw-mb-2 tw-overflow-auto">
            <div class="tw-mt-2 tw-mb-1 tw-p-2">
                <div class="input-group mb-1">
                    <input v-model="search" @keyup="filterList" type="text" class="form-control" placeholder="Pesquise por nome, CPF, telefone ou localização" aria-label="Recipient's username" aria-describedby="basic-addon2">
                    <span @click="clearFilter" v-if="search.length > 0" style="cursor: pointer;position: absolute; right: 51px; top: 8px;z-index:9999"><i class="material-icons">clear</i></span>
                    <div class="input-group-append">
                      <span @click="newContact" class="input-group-text tw-cursor-pointer tw-text-white tw-bg-blue-600" ><i class="material-icons">add</i></span>
                    </div>
                </div>
            </div>
            <div class="tw-h-auto">
                <div  @click.prevent="openContact(contact)" v-for="contact in contacts" :class="'tw-select-none tw-bg-white tw-cursor-pointer tw-m-1 tw-p-3 '+ (contact.selected ? 'tw-bg-gray-600 tw-text-white' : '')" >
                    <div style="font-size: medium"> @{{ contact.nome}}</div>
                    <div class="tw-grid tw-grid-cols-2 tw-gap-2 ">
                        <span style="font-size: small">CPF: @{{ formatCPF(contact.cpf) }}</span>
                        <span style="font-size: small">TEL: @{{ formatTelefone(contact.telefone) }}</span>
                    </div>
                </div>
            </div>
        </div>
        <maps ref="mapa" class="sm:tw-col-span-8 tw-col-span-12 tw-flex tw-flex-col tw-row-span-1" :contacts="contacts" icon-path="{{ asset('images/sedeUex.png')}}"></maps>
       
        <div class="modal" id="modal-contact-info" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>@{{ titleModal }}</h4>
                    </div>
                    <div class="modal-body">
                        <form id="form-contact">
                            <div class="row">
                                <div class="form-group col-12 ">
                                    <label for="">Nome</label>
                                    <input id="name" required v-model="contact.nome" type="text" class="form-control" >
                                </div>
                                <div class="form-group col-6">
                                    <label for="">CPF</label>
                                    <the-mask required="true" :mask="['###.###.###-##']" type="text" class="form-control"  v-model="contact.cpf"></the-mask>
                                </div>
                                <div class="form-group col-6">
                                    <label for="">Telefone</label>
                                    <the-mask :mask="['(##) ####-####', '(##) #####-####']" v-model="contact.telefone" required="true" type="text" class="text-center form-control"/>
                                    <input required v-model="contact.telefone" type="text" class="form-control" >
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-6 col-sm-3">
                                    <label for="">CEP</label>
                                    <the-mask required="true" mask="##.###-###" type="text" v-model="contact.cep"  class="form-control" />
                                </div>
                                <div class="form-group col-12 col-sm-9">
                                    <label for="">Logradouro</label>
                                    <input disabled required v-model="contact.logradouro" type="text" class="form-control">
                                </div>
                                <div class="form-group col-4 col-sm-2">
                                    <label for="">Nº</label>
                                    <input id="numero" required v-model="contact.numero" type="text" class="form-control">
                                </div>
                                <div class="form-group col-8 col-sm-2">
                                    <label for="">Complemento</label>
                                    <input v-model="contact.complemento" type="text" class="form-control">
                                </div>
                                <div class="form-group col-12 col-sm-8">
                                    <label for="">Bairro</label>
                                    <input disabled required v-model="contact.bairro" type="text" class="form-control">
                                </div>
                                <div class="form-group col-9 ">
                                    <label for="">Cidade</label>
                                    <input disabled required v-model="contact.localidade" type="text" class="form-control">
                                </div>
                                <div class="form-group col-3">
                                    <label for="">UF</label>
                                    <input disabled required v-model="contact.uf" type="text" class="form-control">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <div class="col">
                            <div v-if="msgError" class="alert alert-danger">@{{msgError}}</div>
                            <button data-dismiss="modal" class="btn btn-secondary float-left">Cancelar</button>
                            <button @click="saveContact" type="button" class="btn btn-primary float-right">Salvar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</contact-component>
@endsection
@push('scripts')
    <script src="{{ env('API_MAPS') }}"></script>
    <script type="text/javascript">
        var componentVue = {
            'contact-component':{
                props:{
                    api: String,
                },
                data() {
                    return {
                        search:'',
                        original:[],
                        contacts: [],
                        numClicks:0,
                        titleModal: 'Novo Contato',
                        contact:{
                            cep:'',
                            cpf:'',
                            nome:''
                        },
                        msgError: '',
                    }
                },
                watch:{
                    contacts:function(){
                        this.$nextTick(()=>{
                            this.$refs.mapa.addMarkerContacts();
                        })
                    },
                    'contact.cep': function(){
                        this.searchAddress();
                    },
                    'contact.cpf': function(){
                        this.validarCPF();
                    },
                    'contact.nome':function(){
                        this.formatName();
                    }
                },
                mounted() {
                    this.carga();
                },                
                methods: {
                    carga(){
                        this.original = [];
                        this.contacts = [];
                        axios.get(this.api+'contact')
                        .then(function(response){
                            response.data.map(function(c){
                                c.selected = false; 
                                this.contacts.push(c) ;
                                this.original = Object.assign([],this.contacts);

                            }.bind(this))
                            setTimeout(() => {
                                this.$refs.mapa.addMarkerContacts();
                            }, 500);
                        }.bind(this))
                         .catch(error => {alert('error');})
                         .finally(()=>{closeLoadOverlay()});
                    },
                    formatCPF(cpf){
                        return formatarCPF(cpf);
                    },
                    formatTelefone(tel){
                        return formatarTelefone(tel);
                    },
                    formatName(){
                        var name = this.contact.nome.toLowerCase().split(' ');
                        var nameArray = [];
                            
                        for(var x = 0; x < name.length; x++){
                            nameArray.push(name[x].charAt(0).toUpperCase()+name[x].slice(1));
                        }
                        this.contact.nome = nameArray.join(' ');
                    },
                    validarCPF(){
                        // result = 1 LIBERADO
                        // result = 2 INVÁLIDO
                        // result = 3 JÁ CADASTRADO
                        // result = 4 INCOMPLETO
                        if(this.contact.cpf.length == 11){
                            if(!validaCpf(this.contact.cpf)){
                                this.msgError = "CPF Inválido!";
                                setTimeout(() => {
                                    this.msgError = "";
                                }, 3000);
                                return '2';
                            }else{
                                
                               return axios.post(this.api+'validate-cpf',{cpf:this.contact.cpf,id:this.contact.id})
                                    .then(function(response){
                                        if(response.data){
                                            this.msgError = "CPF já cadastrado!";    
                                            setTimeout(() => {
                                                this.msgError = "";    
                                            }, 3000);
                                            return '3';
                                        }else{
                                            return '1';
                                        }
                                    }.bind(this)).catch(error => {
                                        alert('Erro ao consultar CPF! Tente novamente!');
                                    });
                                
                            }
                        }else{
                            return '4';
                        }
                        
                    },
                    searchAddress(){
                        if(this.contact.cep.length == 8){
                            if(!this.contact.id){
                                this.clearAddress();
                            }
                            openLoadOverlay();
                            axios.get('https://viacep.com.br/ws/' + this.contact.cep + '/json/')
                                .then((response)=>{
                                    if(response.data.erro){
                                        this.msgError = 'CEP inválido!';
                                        setTimeout(() => {
                                            this.msgError = '';
                                        }, 3000);
                                    }else{
                                        $("#numero").focus();
                                        response.data.complemento = '';
                                        Object.assign(this.contact,response.data);
                                    }
                                }).catch(error => {
                                    alert('error');
                                }).finally(()=>{closeLoadOverlay()});
                                
                        }
                    },
                    clearAddress(){
                        this.contact.logradouro = '';
                        this.contact.numero = '';
                        this.contact.complemento = '';
                        this.contact.bairro = '';
                        this.contact.localidade = '';
                        this.contact.uf = '';
                    },
                    clearFilter(){
                        this.search = '';
                        this.filterList();
                    },
                    filterList(){
                        this.contacts.map((e)=>{e.selected = false});
                        this.contacts = [];
                        this.contacts = this.original.filter(function(c){
                                            return c.nome.toLowerCase().includes(this.search.toLowerCase()) || 
                                                   c.cpf.toLowerCase().includes(this.search.toLowerCase()) || 
                                                   c.telefone.toLowerCase().includes(this.search.toLowerCase()) ||
                                                   c.localidade.toLowerCase().includes(this.search.toLowerCase()) ||
                                                   c.uf.toLowerCase().includes(this.search.toLowerCase());

                                        }.bind(this));                       
                    },
                    openContact(contact){
                        this.numClicks++;
                        if (this.numClicks === 1) {           
                            var self = this;
                            setTimeout(function() {
                                switch(self.numClicks) {      
                                    case 1:
                                        this.focusMap(contact);
                                        break;
                                    default:
                                        this.editContact(contact);
                                }
                                self.numClicks = 0;                
                            }.bind(this), 300);                               
                        } 
                    },
                    focusMap(contact){
                        this.contacts.map((e)=>{e.selected = false});
                        contact.selected = true;
                        this.$refs.mapa.openInfo(contact.id);
                    },
                    editContact(contact){
                        this.focusMap(contact);                      
                        this.titleModal = 'Edição de Contato';
                        this.contact = Object.assign({},contact);
                        $("#modal-contact-info").modal('show');
                    },
                    newContact(){
                        this.titleModal = 'Novo Contato';
                        this.contact = {cep:'',cpf:'',nome:''};
                        $("#modal-contact-info").modal('show');
                        $('#name').focus();
                    },
                    saveContact(){
                        
                        if($("#form-contact")[0].checkValidity()){
                            if(this.validarCPF() == 4){
                                this.msgError = "CPF Incompleto!";
                                setTimeout(() => {
                                    this.msgError = "";
                                }, 3000);
                            }else{
                                openLoadOverlay();
                                this.validarCPF().then((r)=>{
                                    if(r == '1'){
                                        if(this.contact.telefone.length < 8){
                                            closeLoadOverlay();
                                            this.msgError = "Telefone Inválido!";
                                            setTimeout(() => {
                                                this.msgError = "";
                                            }, 3000);
                                        }else{
                                            this.saveContactFinish();
                                        }
                                    }else{
                                        closeLoadOverlay();
                                        this.msgError = "CPF já cadastrado!";
                                        setTimeout(() => {
                                            this.msgError = "";
                                        }, 3000);
                                    }
                                });
                                
                            }
                        }else{
                            $("#form-contact")[0].reportValidity()
                        }
                    },
                    saveContactFinish(){
                        
                        axios.post(this.api+'contact',{contact:this.contact})
                            .then(response => {
                                let status = response.data.status;
                                if(status === 2){
                                    closeLoadOverlay();
                                    this.msgError = "Erro ao salvar. Confira os dados e tente novamete!";
                                    setTimeout(() => {
                                        this.msgError = '';
                                    }, 3000);
                                }else{
                                    this.contact = {cep:'',cpf:'',nome:''};
                                    this.carga();
                                    $("#modal-contact-info").modal('hide');
                                }
                            }).catch(error => {
                                closeLoadOverlay();
                                alert("Erro ao salvar o contato! Tente novamente!");
                            });
                    }
                    
                }
            }
        }
    </script> 
@endpush
    


