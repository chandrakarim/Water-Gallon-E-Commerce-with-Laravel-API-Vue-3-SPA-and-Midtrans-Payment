<template>
  <div class="bg-white rounded-xl shadow overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
        <tr>
          <slot name="head"></slot>
        </tr>
      </thead>
      <tbody class="divide-y">
        <slot name="body"></slot>
      </tbody>
    </table>
  </div>
</template>