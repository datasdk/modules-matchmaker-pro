<template>
  <section>


    <Loading v-if="loading"/>


    <div v-else>



        <form @submit.prevent="submit">


            <table class="table" >
                
                
                <tr>
                    <th colspan="2">

                        Opret opgave
                        
                    </th>
                </tr>

                <tr>
                    <td width="150">Overskrift</td>
                    <td> 
                     
      
                        <TextField 
                            name="name" 
                            v-model="input.name" 
                        />

                        
                    </td>
                </tr>

        

                <tr>
                    <td>Indhold</td>
                    <td> 
                        
  
                        <TextEditor 
                            name="description" 
                            v-model="input.description"
                            :toolbar="toolbar"
                        />


                    </td>
                </tr>

                <tr>
                    <td width="150">Vælg pris</td>
                    <td> 
                        
                        <v-row>
                            <v-col>
                                
                                <input type="number" name="phone" v-model="input.price" autocomplete="none"  class="form-control" onfocus="this.select()"/> 
                                
                            </v-col>
                            <v-col>DKK</v-col>
                        </v-row>
                        

                    </td>
                </tr>


                <tr>
                    <td>Antal</td>
                    <td> 
                        
                        <v-row>
                            <v-col>
                                
                                <input type="number" name="amount" v-model="input.amount" autocomplete="none"  class="form-control"/> 
                                
                            </v-col>
                    
                        </v-row>
                        
                    </td>
                </tr>


                <tr>
                    <td>Status</td>
                    <td>
                        <v-row>
                            <v-col>

                                <v-select
                                    v-model="input.status"
                                    :items="statusOptions"
                                    item-value="value"
                                    item-text="text"
                                    label="Vælg status"
                                    dense
                                    outlined
                                    class="w-100"
                                ></v-select>

                            </v-col>
                        </v-row>
                    </td>
                </tr>


                <tr>
                    <td>Type</td>
                    <td> 
                        

                        <div>

                            <label>
                                
                                <input type="radio" name="type" value="job" v-model="input.type"> 
                                <strong>Projekt-opslag</strong> - Opslag som andre brugere kan sende ansøgninger til.

                            </label>

                        </div>

                        <div>

                            <label>

                                <input type="radio" name="type"  value="application" v-model="input.type"> 
                                <strong>Mandskab</strong> - Opslag til ansøgninger eller udlejning af mandskab.

                            </label>

                        </div>
                        

                    </td>
                </tr>

         

            </table>

            
            <table class="table" >

                <tr>
                    <th colspan="2">

                        Firma
                        
                    </th>
                </tr>

                <tr>
                    <td width="150">Firma</td>
                    <td> 
                     

                        <SelectCompany
                            v-model="input.company_id"
                        />

                    </td>
                </tr>

                <tr v-if="input.company_id">
                    <td>Kontaktperson</td>
                    <td> 
                        
                          <TaskUser
                            :company_id="input.company_id"
                            v-model="input.user_id"
                        />

                    </td>
                </tr>

            </table>

            
            <table class="table" >
                 <tr >
                    <th colspan="2">Billede</th>
                </tr>

                <tr>
                    <td width="150">Billede</td>
                    <td>
                        <ImageSelect
                            name="image" 
                            v-model="input.images"
                        />
                    </td>      
                </tr>
            </table>


            <table class="table" >
                 <tr >
                    <th colspan="2">Datoer</th>
                </tr>

                <tr>
                    <td width="150">Startdato</td>
                    <td>

                        <Datepicker 
                            v-model="input.available.from" 
                        />


                    </td>
                </tr>

                <tr>
                    <td>Slutdato</td>
                    <td>

                        <Datepicker 
                            v-model="input.available.to" 
                        />
                    </td>
                </tr>
            </table>
   

          


            <SelectCategories
                type = "tasks"
                v-model="input.categories"
                :checked="false"
            />

                        

            <table class="table" >

                <tr>
                    <th colspan="2">

                        Adresse
                        <!--
                        <FindOnMap 
                            :lat="input.address.lat" 
                            :lng="input.address.lng"
                            class="float-right"
                        />
                        -->
                </th>
                </tr>


                    <tr>
                        <td width="150">Adresse</td>
                        <td> 
                         
                            <input type="text" name="street" v-model="input.address.street" class="form-control" />

                        </td>
                    </tr>


                    <tr>
                        <td>Post nr</td>
                        <td> 
                            
                            <input type="text" name="post_code" v-model="input.address.post_code"  class="form-control"/>

                        </td>
                    </tr>

                    <tr>
                        <td>By</td>
                        <td> 
                            
                            <input type="text" name="city" v-model="input.address.city"  class="form-control"/>

                    

                        </td>
                    </tr>

      
                    <tr>
                    <td>Land</td>
                    <td> 
                        
                        <select name="country" v-model="input.address.country" class="form-control">
                        <option value="dk">DK</option>
                        </select>
                    

                    </td>
                    </tr>
                </table>
     

           


            <TaskSpecifications
                type=""
                v-model="input.tags"
            />
           


            <table class="table">
                    
                <tr >
                    <th colspan="2">Adgang</th>
                </tr>

                <tr >
                    <td width="150"></td>
                    <td>
                    
                        <PublishSelection
                        v-model="input.access"
                        />


                    </td>
                </tr>

            </table>


            


            <v-btn color="primary" type="submit"  :loading="submitLoading" >{{submitText}}</v-btn>

            <v-btn color="default" @click="goto('module.tasks.index')">Tilbage</v-btn>
        

        </form>
        

    </div>



  </section>
</template>

<script>


import TableEdit    from "@/Mixins/TableEdit"

    
export default {

    
    mixins: [ TableEdit ],



    data(){

        return {
            
            table: "tasks",

            input:  {
                type: undefined,

                company_id: undefined,
                user_id: undefined,

                name: undefined,
                resume: undefined,
                content: undefined,
                image: undefined,
                access: undefined,
                categories: undefined,
                memberships: undefined,
                available: {
                    from: undefined,
                    to: undefined,
                },
                skills: undefined,
                address:{
                    street: undefined,
                    city: undefined,
                    post_code: undefined,
                    country: 'dk'
                },
                contact:{
                    company: undefined,
                    vat_id: undefined,
                    email: undefined,
                    phone: undefined,
                },
                tags: []
                               
            },

            statusOptions: [
                { value: "live", text: "Offentligt" },
                { value: "pending", text: "Afventer opstart" },
                { value: "ongoing", text: "Under udførelse" },
                { value: "closed", text: "Færdigmeldt" },
                { value: "hold", text: "Paused" },
                { value: "cancelled", text: "Annulleret" },
                { value: "draft", text: "Kladde" }
            ],

            ekstra: {},

            defaultInput: null,

            toolbar: [
                [
                    { 
                        header: [false, 1, 2, 3, 4, 5, 6] 
                    }
                ],
                ["bold", "italic", "underline"],
                [
                    { align: "" },
                    { align: "center" },
                    { align: "right" },
                ],
                [
                    { list: "bullet" }
                ],
                ["link"],
                [
                    { color: [] }, 
                    { background: [] }
                ],
                ["clean"] 
            ]

        }
    },

    created(){

        this.defaultInput = this.input

    },

    watch:{

        company_id(val){

            if(val == 0){ 
                 

            }

        }

    },

    methods:{


        async create(){

            let input = this.input
         
            return axios.post(route("api.tasks.tasks.store"), input ).then((res)=>{

                this.$router.push({ name: "module.tasks.index" })

            })

        },


        async update(){

            let input = this.input
     
            return axios.patch(route("api.tasks.tasks.update",{ id: this.id }), input ).then((res)=>{

                this.$router.push({ name: 'module.tasks.index' })

            })

        },


        get(params){

         
            return axios.get(route("api.tasks.tasks.show",params),{ 
               
                params: { 
                  //  tagsAsId: true,
                    include: "available,user,company,categories,address,images",
                    lang: null
                }
            }).then((res)=>{


                let defaultInput = this.input
                          
                let responseData  = res.data.data


                if(!responseData.address){

                    responseData.address = defaultInput.address

                }

                if(!responseData.contact){

                    responseData.contact = defaultInput.contact

                }


                this.input = responseData

                this.loading = false


            })
                 

        }

    }

}
</script>

<style  scoped>
    label{
        font-weight: normal !important;
        cursor: pointer;
    }
</style>>
