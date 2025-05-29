<!-- 
<template>
    <div class="card flex justify-center">
        <Form :initialValues :resolver @submit="onFormSubmit" class="flex flex-col gap-4 w-full sm:w-80">
            <FormField v-slot="$field" name="username" initialValue="" :resolver="zodUserNameResolver" class="flex flex-col gap-1">
                <InputText type="text" placeholder="Username" />
                <Message v-if="$field?.invalid" severity="error" size="small" variant="simple">{{ $field.error?.message }}</Message>
            </FormField>
            <FormField v-slot="$field" name="firstname" initialValue="" :resolver="yupFirstNameResolver" class="flex flex-col gap-1">
                <InputText type="text" placeholder="First Name" />
                <Message v-if="$field?.invalid" severity="error" size="small" variant="simple">{{ $field.error?.message }}</Message>
            </FormField>
            <FormField v-slot="$field" name="lastname" initialValue="" :resolver="valibotLastNameResolver" class="flex flex-col gap-1">
                <InputText type="text" placeholder="Last Name" />
                <Message v-if="$field?.invalid" severity="error" size="small" variant="simple">{{ $field.error?.message }}</Message>
            </FormField>
            <FormField v-slot="$field" name="password" initialValue="" :resolver="customPasswordResolver" class="flex flex-col gap-1">
                <Password type="text" placeholder="Password" :feedback="false" toggleMask fluid />
                <Message v-if="$field?.invalid" severity="error" size="small" variant="simple">{{ $field.error?.message }}</Message>
            </FormField>
            <FormField v-slot="$field" name="details" class="flex flex-col gap-1">
                <Textarea placeholder="Details" />
                <Message v-if="$field?.invalid" severity="error" size="small" variant="simple">{{ $field.error?.message }}</Message>
            </FormField>
            <Button type="submit" severity="secondary" label="Submit" />
        </Form>
    </div>
</template>

<script setup>
import { reactive } from 'vue';
import { valibotResolver } from '@primevue/forms/resolvers/valibot';
import { yupResolver } from '@primevue/forms/resolvers/yup';
import { zodResolver } from '@primevue/forms/resolvers/zod';
import * as v from 'valibot';
import * as yup from 'yup';
import { z } from 'zod';
import { useToast } from 'primevue/usetoast';

const toast = useToast();

const initialValues = reactive({
    details: ''
});

const resolver = zodResolver(
    z.object({
        details: z.string().min(1, { message: 'Details is required via Form Resolver.' })
    })
);

const zodUserNameResolver = zodResolver(z.string().min(1, { message: 'Username is required via Zod.' }));
const yupFirstNameResolver = yupResolver(yup.string().required('First name is required via Yup.'));
const valibotLastNameResolver = valibotResolver(v.pipe(v.string(), v.minLength(1, 'Last name is required via Valibot.')));

const customPasswordResolver = ({ value }) => {
    const errors = [];

    if (!value) {
        errors.push({ message: 'Password is required via Custom.' });
    }

    return {
        errors
    };
};

const onFormSubmit = ({ valid }) => {
  if (valid) {
    toast.add({ severity: 'success', summary: 'Form is submitted.', life: 3000 });
  }
};
</script> -->


<!-- <template>
    <div class="card flex justify-center">
        <Checkbox v-model="checked" binary />
    </div>
</template>

<script setup>
import { ref } from "vue";

const checked = ref(false);
</script> -->
