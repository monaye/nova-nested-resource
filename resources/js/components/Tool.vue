<template>
  <div>
    <heading class="mb-6">カテゴリー</heading>

    <div class="flex" style="">
      <div class="flex-no-shrink ml-auto mb-3">
        <router-link
          dusk="create-button"
          class="btn btn-default btn-primary"
          :to="{
            name: 'category-create',
          }"
          >Create Category
        </router-link>
      </div>
    </div>
    <loading-view :loading="loading">
      <card class="overflow-hidden">
        <table class="table w-full" cellpadding="0" cellspacing="0">
          <thead class="rounded-t">
            <tr>
              <th class="w-16 text-left">ID</th>
              <th class="text-left">名前</th>
              <th class="text-left"></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="cat in categories.data" :key="cat.id">
              <td>{{ cat.id }}</td>
              <td :class="'level-' + cat.level">
                <span
                  class="block relative"
                  :style="{ paddingLeft: (cat.level - 1) * 25 + 'px' }"
                  ><span
                    class="absolute"
                    :style="{
                      borderLeft: cat.level > 1 ? '1px solid #ddd' : 'none',
                      borderBottom: cat.level > 1 ? '1px solid #ddd' : 'none',
                      top: '-22px',
                      height: '30px',
                      width: cat.level > 1 ? '15px' : '0',
                      left:
                        cat.level > 1 ? (cat.level - 1) * 25 - 20 + 'px' : 0,
                    }"
                  ></span
                  >{{ cat.name }}</span
                >
              </td>
              <td class="td-fit text-right pr-6 align-middle">
                <div class="inline-flex items-center">
                  <router-link
                    class="inline-flex cursor-pointer text-70 hover:text-primary mr-3"
                    :dusk="`${cat.id}-edit-button`"
                    :to="{
                      name: 'category-edit',
                      params: {
                        categoryId: cat.id,
                        category: cat,
                      },
                    }"
                    v-tooltip.click="__('Edit')"
                  >
                    <icon type="edit" />
                  </router-link>
                  <button
                    :data-testid="`${cat.id}-delete-button`"
                    :dusk="`${cat.id}-delete-button`"
                    class="inline-flex appearance-none cursor-pointer text-70 hover:text-primary mr-3"
                    @click.prevent="openDeleteModal(cat.id)"
                  >
                    <icon />
                  </button>
                  <portal
                    to="modals"
                    transition="fade-transition"
                    v-if="deleteModalOpen"
                  >
                    <delete-resource-modal
                      v-if="deleteModalOpen"
                      @confirm="confirmDelete"
                      @close="closeDeleteModal"
                      mode="delete"
                    >
                      <div slot-scope="{ uppercaseMode, mode }" class="p-8">
                        <heading :level="2" class="mb-6">{{
                          __(uppercaseMode + " Resource")
                        }}</heading>
                        <p class="text-80 leading-normal">
                          {{
                            __(
                              "Are you sure you want to " +
                                mode +
                                " this category?"
                            )
                          }}
                        </p>
                      </div>
                    </delete-resource-modal>
                  </portal>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
        <div class="bg-20 rounded-b">
          <nav class="flex justify-between items-center">
            <!-- Previous Link -->
            <button
              :disabled="!categories.prev_page_url || linksDisabled"
              class="btn btn-link py-3 px-4"
              :class="{
                'text-primary dim': categories.prev_page_url,
                'text-80 opacity-50':
                  !categories.prev_page_url || linksDisabled,
              }"
              rel="prev"
              @click.prevent="selectPreviousPage"
              dusk="previous"
            >
              {{ __("Previous") }}
            </button>

            <span class="text-sm text-80 px-4">
              {{ resourceCountLabel }}
            </span>

            <button
              :disabled="!categories.next_page_url || linksDisabled"
              class="btn btn-link py-3 px-4"
              :class="{
                'text-primary dim': categories.next_page_url,
                'text-80 opacity-50':
                  !categories.next_page_url || linksDisabled,
              }"
              rel="next"
              @click.prevent="selectNextPage"
              dusk="next"
            >
              {{ __("Next") }}
            </button>
          </nav>
        </div>
      </card>
    </loading-view>
  </div>
</template>

<script>
export default {
  data: () => ({
    categories: {},
    linksDisabled: false,
    loading: true,
    deleteModalOpen: false,
  }),
  metaInfo() {
    return {
      title: "NovaNestedResourceTools",
    };
  },
  async mounted() {
    console.log("mounted");
    this.getCategories();
  },
  computed: {
    /**
     * Return the resource count label
     */
    resourceCountLabel() {
      return (
        this.categories.data &&
        `${this.categories.from} - ${this.categories.to} ${this.__(
          "of"
        )} ${Nova.formatNumber(this.categories.total)}`
      );
    },
  },
  methods: {
    selectPreviousPage() {
      this.getCategories(this.categories.current_page - 1);
    },
    selectNextPage() {
      this.getCategories(this.categories.current_page + 1);
    },
    async getCategories(page = 1) {
      //   this.loading = true;
      const categories = await Nova.request().get("/categories?page=" + page);
      console.log("categories", categories);
      if (categories.status !== 200) {
        // throw some error
        console.log("handle error", categories.status);
      }
      this.loading = false;
      this.categories = categories.data;
    },
    openDeleteModal(categoryId) {
      this.deleteCategoryId = categoryId;
      this.deleteModalOpen = true;
    },

    confirmDelete(categoryId) {
      console.log("confirm delete", categoryId);
      this.deleteResource(categoryId);
      this.closeDeleteModal();
    },

    closeDeleteModal() {
      this.deleteCategoryId = null;
      this.deleteModalOpen = false;
    },
    /**
     * Delete the given resources.
     */
    async deleteResource() {
      const result = await Nova.request({
        url: "/categories/",
        method: "delete",
        params: {
          data: {
            categoryId: this.deleteCategoryId,
          },
        },
      });
      if (result.status !== 200) {
        console.log("error delete", result);
        Nova.error(
          "カテゴリーの削除に失敗しました。一度ログアウトしもう一度お試しください。"
        );
      }
      this.getCategories(this.categories.current_page);
      Nova.success("カテゴリーを削除しました。");
    },
  },
};
</script>

<style>
/* Scoped Styles */
</style>
