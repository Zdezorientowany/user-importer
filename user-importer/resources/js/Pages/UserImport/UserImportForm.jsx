import React from "react";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { z } from "zod";
import { Button } from "@/components/ui/button";
import {
    Form,
    FormControl,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from "@/components/ui/form";
import { Input } from "@/components/ui/input";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { router } from "@inertiajs/react";

const formSchema = z.object({
    file: z.instanceof(File).refine((file) => file.type === "text/csv", {
        message: "Only CSV files are accepted.",
    }),
});

export default function ImportForm() {
    const form = useForm({
        resolver: zodResolver(formSchema),
        defaultValues: {
            file: null,
        },
    });

    const onSubmit = (values) => {
        router.post(route("user-imports.store"), values, {
            onSuccess: () => form.reset(),
        });
    };

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Import Users
                </h2>
            }
        >
            <div className="py-12">
                <div className="mx-auto max-w-2xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <div className="p-6 text-gray-900 dark:text-gray-100">
                            <Form {...form}>
                                <form
                                    onSubmit={form.handleSubmit(onSubmit)}
                                    className="space-y-6"
                                >
                                    <FormField
                                        control={form.control}
                                        name="file"
                                        render={({ field }) => (
                                            <FormItem>
                                                <FormLabel>
                                                    Upload CSV File
                                                </FormLabel>
                                                <FormControl>
                                                    <Input
                                                        type="file"
                                                        accept=".csv"
                                                        className="cursor-pointer"
                                                        onChange={(e) =>
                                                            field.onChange(
                                                                e.target
                                                                    .files[0]
                                                            )
                                                        }
                                                    />
                                                </FormControl>
                                                <FormMessage />
                                            </FormItem>
                                        )}
                                    />
                                    <div className="flex justify-end">
                                        <Button type="submit">Import</Button>
                                    </div>
                                </form>
                            </Form>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
