<template>
  <section>


    <div class="content-header">

      <h1>
        Opgaver
        <small>Her er en oversigt over alle dine opgaver</small>
      </h1>
      
      <v-btn color="primary" @click="goto('module.tasks.create')">Opret opgave</v-btn>  

    </div>

      <v-card>
       


          <Table
            :headers="headers"    
            :table="table"
            :route="route"
            :include="include"
            :move="false"
            
            :options="options"
          >
          
          
            <template v-slot:item.name="{ item }">
              <span v-if="item.name">
            
                {{ item.name }}

              </span>
            </template>

            <template v-slot:item.user_id="{ item }">
             
                {{ item.user?.first_name }} {{ item.user?.last_name  }}

            </template>


            <template v-slot:item.tags="{ item }">
              <div v-if="item.tags">
              
               <TableTags :tags="item.tags"/>

              </div>
            </template>


            <template v-slot:item.info="{ item }">
              <div class="p-2">

                <div v-if="item.company">
                  <div><strong>{{ item.company?.name }}</strong></div>
                  <div>Cvr. {{ item.company?.vat }}</div>
                </div>
                

                <div>{{ item.user?.first_name }} {{ item.user?.last_name }}</div>

              </div>
            </template>
            
            <template v-slot:item.login="{ item }">
              <section v-if="item.user">

                <TableLoginButton 
                  :user_id="item.user?.id" 
                  :params="getTaskParams(item)"
                  name="tasks"
                />

              </section>
            </template>

            <template v-slot:item.status="{ item }">
              <div v-if="item" class="p-2">
                

                <v-chip color="secondary" small label class="mb-2">Status: {{ item.status }}</v-chip>

                <v-chip color="yellow" small label class="mb-2">Matches: {{ item.matches_count }}</v-chip>

                <v-chip color="green" small label class="mb-2">Hyret: {{ item.hires_count }}</v-chip>


              </div>
            </template>

          </Table>

      </v-card>




  </section>
</template>


<script>

import TableIndex from "@/Mixins/TableIndex";
import TableLoginButton from "@/Components/table/collums/TableLoginButton.vue"


export default {

  mixins: [TableIndex],

  components:{
    TableLoginButton
  },

  data(){

    return {

      table: "tasks",
      route: "tasks.tasks",

      include: "company,user,tags",

      headers: [
          { text: 'Id', value: 'uid' },
          { text: 'Navn', value: 'name' },
       //   { text: 'Billede', value: 'image' },
          { text: 'Information', value: 'info' },
          { text: 'Specifikationer', value: 'tags' },
          { text: 'Status', value: 'status' },
          { text: 'Login', value: 'login' },
          { text: 'Dato', value: 'created_at' },    
          { text: '', value: 'actions' },
      ],

      options: ["categories","memberships"]

    }
    
  },

  methods:{

    getTaskParams(item){

      return {
        id: item.id,
        type: item.type,
        price: item.price,
        company_id: item.company_id,
        amount: item.amount,
        status: item.status,
        search_distance: item.search_distance,
      }
                    
    }

  }

}

</script>

<style >


</style>