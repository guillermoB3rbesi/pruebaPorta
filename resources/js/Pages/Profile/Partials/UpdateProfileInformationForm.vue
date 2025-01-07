<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from "vue";
import { VueTelInput } from 'vue-tel-input';
import 'vue-tel-input/vue-tel-input.css';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;
const form = useForm({
    name: user.name,
    email: user.email,
    avatar_img: '',
    phone: user.phone
});

const isValidPhone = ref(false);

const patchProfile = () => {
    if (isValidPhone.value === false) {
        form.errors.phone = 'Invalid phone number';
        return;
    }
    form.errors.phone = '';
    form.post(route('profile.update'));
};

const urlTemp = computed(() => {
    if (!form.avatar_img)
        return `/${user.avatar_temp}`;
    return URL.createObjectURL(form.avatar_img);
});
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Profile Information
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Update your account's profile information and email address.
            </p>
        </header>

        <form
            @submit.prevent="patchProfile"
            class="mt-6 space-y-6"
        >
            <div>
                <InputLabel for="avatar" value="Avatar" />
                <label for="avatar">
                    <img
                        class="mx-auto h-[200px] w-[200px] rounded-full"
                        :src="urlTemp"
                    />
                </label>
                <input
                    type="file"
                    id="avatar"
                    name="avatar"
                    class="hidden"
                    @input="form.avatar_img = $event.target.files[0]"
                />
                <InputError class="mt-2" :message="form.errors.avatar_img" />
            </div>
            <div>
                <InputLabel for="name" value="Name" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="phone" value="Phone" />

                <vue-tel-input
                    id="phone"
                    v-model="form.phone"
                    @validate="(telInput) => (isValidPhone = telInput.valid)"
                    mode="international"
                ></vue-tel-input>

                <InputError class="mt-2" :message="form.errors.phone" />
            </div>

            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="mt-2 text-sm text-gray-800">
                    Your email address is unverified.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        Click here to re-send the verification email.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 text-sm font-medium text-green-600"
                >
                    A new verification link has been sent to your email address.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-gray-600"
                    >
                        Saved.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
