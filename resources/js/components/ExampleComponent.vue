
<template>
    <div class="card flex justify-content-center">
        <Button type="button" icon="pi pi-image" label="Image" @click="toggle(123)" />
        <OverlayPanel ref="op">
            <p>This is id = {{  itemID}}</p>
            <div class="flex flex-column gap-3 w-25rem">
                <Button label='Edit'/>
                <Button label='Delete'/>
            </div>
        </OverlayPanel>
    </div>
</template>

<script setup>
import { ref } from "vue";

const op = ref();
const itemID = ref(null);
const members = ref([
    { name: 'Amy Elsner', image: 'amyelsner.png', email: 'amy@email.com', role: 'Owner' },
    { name: 'Bernardo Dominic', image: 'bernardodominic.png', email: 'bernardo@email.com', role: 'Editor' },
    { name: 'Ioni Bowcher', image: 'ionibowcher.png', email: 'ioni@email.com', role: 'Viewer' }
]);

const toggle = (id) => {
    itemID.value= id
    op.value.toggle(event);
}
</script>
