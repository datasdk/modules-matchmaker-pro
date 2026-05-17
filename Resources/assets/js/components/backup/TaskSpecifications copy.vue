<template>
  <section>


    <table class="table">
                    
        <tr >
            <th colspan="2">Ekstra</th>
        </tr>

        <tbody v-for="item in input" :key="item.id">

            <tr>
                <td width="150">

                    {{item.label}}

                </td>
                <td>


                    <div v-if="is_object(item.value)">


                        <span v-for="value in item.value" :key="value.name">
                            
                            <div><label><input type="checkbox" :value="value.value" > {{value.label}}</label></div>

                        </span>                     


                    </div>

                    <div v-else>

                        <label><input type="text" :value="item.value.value" > {{item.value.label}}</label>

                    </div>

                    
                        
                </td>
            </tr>

        </tbody>

        <tr>
            <td colspan="2">
                

                <button>Ny linje</button>


            </td>
        </tr>

    </table>

  </section>
</template>

<script>


export default {

    components:{

    },

    data(){

        return {

            input: [
                { 
                    id: 1, 
                    name: "money", 
                    label: "Penge", 
                    value:  [
                        { label: "100kr", value: 100 },
                        { label: "200kr", value: 200 },
                        { label: "300kr", value: 300 }
                    ]
                },
                {
                    id: 2,
                    name: ""
                }
            ]

        }

    },
    
    methods:{

        is_object(item){

            return Array.isArray(item)

        }
        

    }

}
</script>

<style>

</style>