<template>
  <section>



    <Loading v-if="loading" />

    <div v-else>
      

      <table class="table">

        <th colspan="2">

          Specifikationer (tags)

        </th>
        
        <tr v-for="(tags, groupName) in groupedTags" :key="groupName">

          <td width="150">

            {{ groupName }}

          </td>
 
          <td>
            
            <div v-for="item in tags" :key="item.id" class="w-25 float-left">
              
              <label>
                          
                <input
                  type="checkbox"
                  :value="item.slug"
                  v-model="input"
                />
                
                {{ item.name }}
                        
              </label>

            </div>

            
          </td>
          
        </tr>

      </table>


    </div>


  </section>
</template>


<script>

import axios from 'axios';

import collect from 'collect.js'; // Import collect.js


export default {

  props:{

    value: {
      require: true
    }

  },

  data() {

    return {

      tags: [], // Holds the fetched tags
      loading: true,
      input: this.value,
      selectedTags: [], // Holds selected tag IDs
      groupedTags: {} // Holds grouped tags by type

    }

  },

  async mounted() {


    try {

      const res = await axios.get(route("api.tags.index"),{
        tagsAsId: true,
        params:{
          lang: this.$i18n.locale 
        }
      });
      
      // Flet tags baseret på type
      this.groupedTags = collect(res.data.data).sortBy("name").groupBy("type").all();

    } catch (error) {

      console.error('Error fetching tags:', error);

    } finally {

      this.loading = false;

    }

  },

  watch: {

    input() {

      // Emit the selected tags when there is a change
      this.$emit('input', this.input);

    }

  }
};
</script>

<style lang="scss">
/* Tilføj eventuelle brugerdefinerede stilarter her */
</style>
