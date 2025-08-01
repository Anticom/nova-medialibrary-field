<template>
  <div class="flex items-center justify-between px-2 py-1">
    <div class="flex items-center">
      <button v-if="canView" type="button" class="flex hover:opacity-50 focus:outline-none" @click="media.view()">
        <Icon name="eye" class="icon-sm" />
      </button>

      <button v-if="canEdit" type="button" class="ml-2 flex hover:opacity-50 focus:outline-none" @click="media.edit()">
        <Icon name="pencil-alt" class="icon-sm" />
      </button>

      <button
        v-if="canDelete"
        type="button"
        class="ml-2 flex hover:opacity-50 focus:outline-none"
        @click="media.openDeleteModal()"
      >
        <Icon name="trash" class="icon-sm" />
      </button>
    </div>

    <Dropdown placement="bottom-start" class="btn-block place-self-end">
      <DropdownTrigger :show-arrow="false" class="h-6 w-6 hover:opacity-50">
        <Icon name="dots-horizontal" type="solid" class="icon-sm" />
      </DropdownTrigger>

      <template #menu>
        <DropdownMenu>
          <div class="flex flex-col py-1">
            <DropdownMenuItem
              as="button"
              v-if="!hideCopyUrlAction"
              @click="doCopy($event, 'downloadUrl')"
              class="flex py-1 hover:bg-gray-100"
            >
              <Icon name="clipboard-copy" class="icon-md" />
              <span class="ml-2">{{ __('Copy Url') }}</span>
            </DropdownMenuItem>

            <DropdownMenuItem
              as="button"
              class="flex items-center hover:bg-gray-100"
              v-for="copyAs in media.copyAs"
              :key="copyAs.as"
              @click="doCopy($event, copyAs.as)"
            >
              <Icon :name="copyAs.icon" class="icon-md" />
              <span class="ml-2">{{ __(`Copy as ${copyAs.as}`) }}</span>
            </DropdownMenuItem>

            <DropdownMenuItem
              as="button"
              v-if="canCrop"
              class="flex items-center hover:bg-gray-100"
              @click="media.openCropperModal()"
            >
              <icon-crop width="20" height="20" />
              <span class="ml-2">{{ __('Crop') }}</span>
            </DropdownMenuItem>

            <DropdownMenuItem
              as="button"
              v-if="canRegenerate"
              class="flex items-center hover:bg-gray-100"
              @click="media.regenerate()"
            >
              <Icon name="refresh" />
              <span class="ml-2">{{ __('Regenerate') }}</span>
            </DropdownMenuItem>
          </div>
        </DropdownMenu>
      </template>
    </Dropdown>
  </div>
</template>

<script>
import { Icon } from 'laravel-nova-ui'
import { context } from './Context'

export default {
  components: {
    Icon,
  },

  props: {
    media: {
      type: Object,
      required: true,
    },
  },

  inject: {
    context,
  },

  methods: {
    async doCopy(event, as) {
      await this.media.copy(as, event.target)
    },
  },

  computed: {
    readonly() {
      return this.context.field.readonly
    },
    canEdit() {
      return this.media.authorizedToUpdate && !this.readonly
    },
    canView() {
      return this.media.authorizedToView
    },
    canDelete() {
      return this.media.authorizedToDelete && !this.readonly
    },
    canCrop() {
      return this.media.cropperEnabled && !this.readonly
    },
    canRegenerate() {
      return !this.readonly
    },
    hideCopyUrlAction() {
      return this.context.field.hideCopyUrlAction
    },
  },
}
</script>
