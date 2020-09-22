<template>
  <div class="produtos">
    <tabela
      :card-width="cardWidth"
      :colunas="[
        {name: 'CÓDIGO', align: 'text-center', width: 15},
        {name: 'DESCRIÇÃO', align: 'text-left', width: 67},
        {name: 'OPERAÇÕES', align: 'text-center', width: 18}
      ]"
      :regs="produtos"
      ordem="asc" ord-col="1"
      url="products"
      token="token"
    ></tabela>    
  </div>
</template>

<script>
  export default {
    props: ['produtos', 'card-width', 'token']
  }
</script>

<style scoped lang="scss">

</style>
