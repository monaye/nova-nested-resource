<template>
  <div>
    <h1>Detail View</h1>
    <h3>{{ category ? category.name : "no category" }}</h3>
    <div class="relative">
      <div class="mb-3"></div>
      <form autocomplete="off">
        <div class="mb-8">
          <h1 class="text-90 font-normal text-2xl mb-3">
            業務項目:{{ cat.name }}を更新
          </h1>
          <!---->
          <div class="card">
            <div class="flex border-b border-40" resource-id="203">
              <div class="w-1/5 px-8 py-6">
                <label
                  for="parent_id"
                  class="inline-block text-80 pt-2 leading-tight"
                >
                  親分野名&nbsp;<!----></label
                >
              </div>
              <div class="py-6 px-8 w-1/2">
                <div class="w-full">
                  <div class="relative">
                    <div
                      tabindex="0"
                      class="flex items-center form-control form-input form-input-bordered pr-6 form-select"
                    >
                      <div
                        class="search-input-trigger absolute pin select-box"
                      ></div>
                      <div class="flex items-center">特定行政書士業務</div>
                    </div>
                    <!---->
                  </div>
                  <!---->
                </div>
                <!---->
                <!---->
              </div>
            </div>
            <div
              class="flex border-b border-40 remove-bottom-border"
              resource-id="203"
            >
              <div class="w-1/5 px-8 py-6">
                <label
                  for="name"
                  class="inline-block text-80 pt-2 leading-tight"
                >
                  分野名&nbsp;<!----></label
                >
              </div>
              <div class="py-6 px-8 w-1/2">
                <input
                  id="name"
                  dusk="name"
                  list="name-list"
                  type="text"
                  placeholder="分野名"
                  class="w-full form-control form-input form-input-bordered"
                />
                <!---->
                <!---->
                <!---->
              </div>
            </div>
          </div>
        </div>
        <div class="flex items-center">
          <cancel-button @click="$router.back()" />
          <button
            type="button"
            class="btn btn-default btn-primary inline-flex items-center relative mr-3"
            dusk="update-and-continue-editing-button"
          >
            <span class=""> 更新 &amp; 編集を続ける </span>
            <!---->
          </button>
          <button
            type="submit"
            class="btn btn-default btn-primary inline-flex items-center relative"
            dusk="update-button"
          >
            <span class=""> 業務項目を更新 </span>
            <!---->
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  data: () => ({}),
  metaInfo() {
    return {
      title: "Category Details",
    };
  },
  async mounted() {
    console.log("mounted");
  },
  props: {
    category: Object,
  },
  computed: {},
  methods: {},
};
</script>

<style>
/* Scoped Styles */
</style>
